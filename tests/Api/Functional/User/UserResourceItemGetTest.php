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
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
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
     */
    public function testUserItemGetNoAuth(): void
    {
        $this->checkItemGet(
            client: static::createClient(),
            contains: [
                'fullName' => UserFixtures::DATA[0]['full_name'],
                'username' => UserFixtures::DATA[0]['username'],
                'email' => UserFixtures::DATA[0]['email'],
            ],
            hasKey: [
                'id',
                'fullName',
                'username',
                'email',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'confirmationToken',
                'passwordRequestedAt',
                'plainPassword',
                'enabled',
                'groups',
                'roles',
                'lastLogin',
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
     */
    public function testUserItemGetUserAuth(): void
    {
        $this->checkItemGet(
            client: $this->createClientWithCredentials(),
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
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'confirmationToken',
                'passwordRequestedAt',
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
     */
    public function testUserItemGetSuperAdminAuth(): void
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
                'confirmationToken',
                'passwordRequestedAt',
                'plainPassword',
            ],
        );
    }
}