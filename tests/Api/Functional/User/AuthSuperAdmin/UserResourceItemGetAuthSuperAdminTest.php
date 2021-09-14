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

namespace Curler7\UserBundle\Tests\Api\Functional\User\AuthSuperAdmin;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
use Curler7\UserBundle\Model\UserInterface;
use Curler7\UserBundle\Tests\Api\Functional\User\AbstractUserResourceTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceItemGetAuthSuperAdminTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];
    protected const GLOBAL_METHOD = self::METHOD_GET;

    /**
     * @throws ArrayHasMoreItemsException
     * @throws ArrayNotEmptyException
     * @throws ClientExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws DecodingExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RequestUrlNotFoundException
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemGetAuthSuperAdminSelf(): void
    {
        /** @var User $user */
        $user = self::findOneBy(['username' => UserFixtures::DATA[1]['username']]);

        $this->checkItemGet(
            client: $this->createClientWithCredentials(user: 'admin'),
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'fullName' => UserFixtures::DATA[1]['fullName'],
                'username' => UserFixtures::DATA[1]['username'],
                'email' => UserFixtures::DATA[1]['email'],
                'lastLogin' => (new \DateTime())->format(DATE_W3C),
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN,
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => UserFixtures::DATA[1]['enabled'],
                'verified' => UserFixtures::DATA[1]['verified'],
            ],
            criteria: [
                'username' => UserFixtures::DATA[1]['username']
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'share',
            ],
        );
    }

    /**
     * @throws ArrayHasMoreItemsException
     * @throws ArrayNotEmptyException
     * @throws ClientExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws DecodingExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RequestUrlNotFoundException
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemGetAuthSuperAdminOtherUser(): void
    {
        /** @var User $user */
        $user = self::findOneBy();

        $this->checkItemGet(
            client: $this->createClientWithCredentials(user: 'admin'),
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'fullName' => UserFixtures::DATA[0]['fullName'],
                'username' => UserFixtures::DATA[0]['username'],
                'email' => UserFixtures::DATA[0]['email'],
                'lastLogin' => null,
                'roles' => [
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => UserFixtures::DATA[0]['enabled'],
                'verified' => UserFixtures::DATA[0]['verified'],
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'share',
            ],
        );
    }

    /**
     * @throws ArrayHasMoreItemsException
     * @throws ArrayNotEmptyException
     * @throws ClientExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws DecodingExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RequestUrlNotFoundException
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemGetAuthSuperAdminOtherSuperAdmin(): void
    {
        /** @var User $user */
        $user = self::findOneBy(['username' => UserFixtures::DATA[3]['username']]);

        $this->checkItemGet(
            client: $this->createClientWithCredentials(user: 'admin'),
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'fullName' => UserFixtures::DATA[3]['fullName'],
                'username' => UserFixtures::DATA[3]['username'],
                'email' => UserFixtures::DATA[3]['email'],
                'lastLogin' => null,
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN,
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => UserFixtures::DATA[3]['enabled'],
                'verified' => UserFixtures::DATA[3]['verified'],
            ],
            criteria: [
                'username' => UserFixtures::DATA[3]['username']
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'share',
            ],
        );
    }
}