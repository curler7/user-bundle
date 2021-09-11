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

namespace Curler7\UserBundle\Tests\Api\Functional\User;

use App\DataFixtures\UserFixtures;
use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceItemPutTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];
    protected const GLOBAL_METHOD = self::METHOD_PUT;

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testUserItemPutAuthNoop(): void
    {
        $this->check401(static::createClient());
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testUserItemPutAuthUserOtherUser(): void
    {
        $this->check403(
            $this->createClientWithCredentials(),
            ['username' => UserFixtures::DATA[2]['username']]
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testUserItemPutAuthUserOtherAdmin(): void
    {
        $this->check403(
            $this->createClientWithCredentials(),
            ['username' => UserFixtures::DATA[1]['username']]
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testUserItemPutAuthSuperAdminValidatorLastSuperAdmin(): void
    {
        $this->check422(
            client: $this->createClientWithCredentials(user: 'admin'),
            description: 'enabled: curler7_user.user.enabled.last_super_admin',
            criteria: [
                'username' => UserFixtures::DATA[1]['username'],
            ],
            json: [
                'enabled' => false,
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
    public function testUserItemPutAuthUserSelf(): void
    {
        $this->checkItemPut(
            client: $this->createClientWithCredentials(),
            json: [
                'email' => 'new@example.com',
            ],
            contains: [
                'username' => UserFixtures::DATA[0]['username'],
                'email' => 'new@example.com',
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
                'groups',
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
    public function testUserItemPutAuthSuperAdminOtherSelf(): void
    {
        $this->checkItemPut(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                'email' => 'new@example.com',
            ],
            contains: [
                'username' => UserFixtures::DATA[1]['username'],
                'email' => 'new@example.com',
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
                'groups',
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
    public function testUserItemPutAuthSuperAdminOtherUser(): void
    {
        $this->checkItemPut(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                'email' => 'new@example.com',
            ],
            contains: [
                'username' => UserFixtures::DATA[0]['username'],
                'email' => 'new@example.com',
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'groups',
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
    public function testUserItemPutAuthSuperAdminOtherAdmin(): void
    {
        $this->checkItemPut(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                'email' => 'new@example.com',
            ],
            contains: [
                'username' => UserFixtures::DATA[3]['username'],
                'email' => 'new@example.com',
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
                'groups',
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