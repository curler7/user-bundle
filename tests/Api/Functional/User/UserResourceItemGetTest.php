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
class UserResourceItemGetTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];
    protected const GLOBAL_METHOD = self::METHOD_GET;

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testUserItemGetAuthNoop(): void
    {
        $this->check401(static::createClient());
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
    public function testUserItemGetAuthUserSelf(): void
    {
        $this->checkItemGet(
            client: $this->createClientWithCredentials(),
            contains: [
                'fullName' => UserFixtures::DATA[0]['full_name'],
                'username' => UserFixtures::DATA[0]['username'],
                'email' => UserFixtures::DATA[0]['email'],
                'lastLogin' => (new \DateTime())->format(DATE_W3C),
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
    public function testUserItemGetAuthUserOtherUser(): void
    {
        $this->checkItemGet(
            client: $this->createClientWithCredentials(),
            contains: [
                'fullName' => UserFixtures::DATA[2]['full_name'],
                'username' => UserFixtures::DATA[2]['username'],
                'email' => UserFixtures::DATA[2]['email'],
            ],
            criteria: [
                'username' => UserFixtures::DATA[2]['username']
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
    public function testUserItemGetAuthUserOtherSuperAdmin(): void
    {
        $this->checkItemGet(
            client: $this->createClientWithCredentials(),
            contains: [
                'fullName' => UserFixtures::DATA[1]['full_name'],
                'username' => UserFixtures::DATA[1]['username'],
                'email' => UserFixtures::DATA[1]['email'],
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
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'enabled',
                'groups',
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
    public function testUserItemGetAuthSuperAdminSelf(): void
    {
        $this->checkItemGet(
            client: $this->createClientWithCredentials(user: 'admin'),
            contains: [
                'fullName' => UserFixtures::DATA[1]['full_name'],
                'username' => UserFixtures::DATA[1]['username'],
                'email' => UserFixtures::DATA[1]['email'],
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
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
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
    public function testUserItemGetAuthSuperAdminOtherUser(): void
    {
        $this->checkItemGet(
            client: $this->createClientWithCredentials(user: 'admin'),
            contains: [
                'fullName' => UserFixtures::DATA[0]['full_name'],
                'username' => UserFixtures::DATA[0]['username'],
                'email' => UserFixtures::DATA[0]['email'],
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
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
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
    public function testUserItemGetAuthSuperAdminOtherSuperAdmin(): void
    {
        $this->checkItemGet(
            client: $this->createClientWithCredentials(user: 'admin'),
            contains: [
                'fullName' => UserFixtures::DATA[3]['full_name'],
                'username' => UserFixtures::DATA[3]['username'],
                'email' => UserFixtures::DATA[3]['email'],
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
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
            ],
        );
    }
}