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

use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
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
class UserResourceCollectionPostAuthSuperAdminTest extends AbstractUserResourceTest
{
    protected const GLOBAL_METHOD = self::METHOD_POST;

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserCollectionPostAuthSuperAdminWithNoParameters(): void
    {
        $this->check422(
            client: $this->createClientWithCredentials(user: 'admin'),
            description: 'username: curler7_user.user.username.not_blank
email: curler7_user.user.email.not_blank
password: curler7_user.user.plain_password.not_blank',
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserCollectionPostAuthSuperAdminWithFalseParameters(): void
    {
        $this->check422(
            client: $this->createClientWithCredentials(user: 'admin'),
            description: 'username: curler7_user.user.username.length.min
email: curler7_user.user.email.email
password: curler7_user.user.plain_password.length.min',
            json: [
                'username' => 'a',
                'email' => 'a',
                'password' => 'a',
            ],
        );
    }

    /**
     * @throws ArrayHasMoreItemsException
     * @throws ArrayNotEmptyException
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserCollectionPostAuthSuperAdminWithMinimalParameters(): void
    {
        $this->checkCollectionPost(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                'username' => 'new',
                'email' => 'corona@smart-media.design',
                'password' => 'password',
            ],
            contains: [
                'username' => 'new',
                'email' => 'corona@smart-media.design',
                'fullName' => null,
                'enabled' => true,
                'lastLogin' => null,
                'roles' => [
                    UserInterface::ROLE_DEFAULT,
                ],
                'verified' => false
            ],
            hasKey: [
                'fullName',
                'id',
                'username',
                'email',
                'enabled',
                'lastLogin',
                'roles',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'loginLinkRequestedAt',
                'plainPassword',
                'password',
                'share',
            ],
        );
    }

    /**
     * @throws ArrayHasMoreItemsException
     * @throws ArrayNotEmptyException
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserCollectionPostAuthSuperAdminWithAllResourceParameters(): void
    {
        $uuid = UuidV4::v4();

        $this->checkCollectionPost(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                // 'id' => $uuid->toRfc4122(),
                'fullName' => 'new',
                'username' => 'new',
                'email' => 'corona@smart-media.design',
                'password' => 'password',
                'enabled' => false,
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN
                ],
                'verified' => true,
                'usernameCanonical' => null,
                'emailCanonical' => null,
                'loginLinkRequestedAt' => (new \DateTime('+2years'))->format(DATE_W3C),
                // 'plainPassword' => 'noop',
                'share' => null,
            ],
            contains: [
                // 'id' => $uuid->toRfc4122(),
                'fullName' => 'new',
                'username' => 'new',
                'email' => 'corona@smart-media.design',
                'enabled' => false,
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN,
                    UserInterface::ROLE_DEFAULT,
                ],
                'verified' => false,
            ],
            hasKey: [
                'id',
                'fullName',
                'username',
                'email',
                'enabled',
                'lastLogin',
                'roles',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'loginLinkRequestedAt',
                'plainPassword',
                'password',
                'share',
            ],
        );
    }
}