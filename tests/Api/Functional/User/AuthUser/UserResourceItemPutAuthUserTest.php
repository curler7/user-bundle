<?php

/*
 * This file is part of the Curler7ApiTestBundle project.
 *
 * (c) Gunnar Suwe <suwe@smart-media.design>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Curler7\UserBundle\Tests\Api\Functional\User\AuthUser;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
use Curler7\UserBundle\Model\UserInterface;
use Curler7\UserBundle\Tests\Api\Functional\User\AbstractUserResourceTest;
use Symfony\Component\Uid\UuidV4;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceItemPutAuthUserTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];
    protected const GLOBAL_METHOD = self::METHOD_PUT;

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserItemPutAuthUserOtherUser(): void
    {
        $this->check403(
            client: $this->createClientWithCredentials(),
            criteria: ['username' => UserFixtures::DATA[2]['username']]
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserItemPutAuthUserOtherAdmin(): void
    {
        $this->check403(
            client: $this->createClientWithCredentials(),
            criteria: ['username' => UserFixtures::DATA[1]['username']]
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserItemPutAuthUserSelfWithFalseParameters(): void
    {
        $this->check422(
            client: $this->createClientWithCredentials(),
            description: 'username: curler7_user.user.username.length.min
email: curler7_user.user.email.email
password: curler7_user.user.plain_password.length.min',
            json: [
                'username' => 'a',
                'email' => 'a',
                'password' => 'a',
            ]
        );
    }

    /**
     * @throws ArrayHasMoreItemsException
     * @throws RequestUrlNotFoundException
     * @throws ArrayNotEmptyException
     * @throws RedirectionExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemPutAuthUserSelfWithMinimalParameters(): void
    {
        /** @var User $user */
        $user = self::findOneBy();

        $this->checkItemPut(
            client: $this->createClientWithCredentials(),
            json: [],
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'fullName' => UserFixtures::DATA[0]['fullName'],
                'lastLogin' => (new \DateTime())->format(DATE_W3C),
                'username' => UserFixtures::DATA[0]['username'],
                'email' => UserFixtures::DATA[0]['email'],
                'roles' => [
                    UserInterface::ROLE_DEFAULT,
                ],
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'enabled',
                'verified',
                'share',
            ],
        );
    }

    /**
     * @throws ArrayHasMoreItemsException
     * @throws RequestUrlNotFoundException
     * @throws ArrayNotEmptyException
     * @throws RedirectionExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemPutAuthUserSelfWithAllResourceParameters(): void
    {
        /** @var User $user */
        $user = self::findOneBy();

        $this->checkItemPut(
            client: $this->createClientWithCredentials(),
            json: [
                'id' => UuidV4::v4()->toRfc4122(),
                'fullName' => 'put user',
                'lastLogin' => (new \DateTime('+2 years'))->format(DATE_W3C),
                'username' => 'put user',
                'email' => 'put@example.com',
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN
                ],
                'usernameCanonical' => null,
                'emailCanonical' => null,
                'password' => 'password',
                'loginLinkRequestedAt' => (new \DateTime('+2 years'))->format(DATE_W3C),
                // 'plainPassword',
                'enabled' => false,
                'verified' => false,
                'share' => true,
            ],
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'fullName' => 'put user',
                'lastLogin' => (new \DateTime())->format(DATE_W3C),
                'username' => 'put user',
                'email' => 'put@example.com',
                'roles' => [
                    UserInterface::ROLE_DEFAULT
                ],
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'enabled',
                'verified',
                'share',
            ],
        );
    }
}