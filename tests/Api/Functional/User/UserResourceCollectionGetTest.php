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
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceCollectionGetTest extends AbstractUserResourceTest
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testUserCollectionGetNoAuth(): void
    {
        $this->checkCollectionGet(
            client: static::createClient(),
            totalItems: \count(UserFixtures::DATA),
            hasKey: [
                'fullName',
                'id',
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
                'groups',
                'enabled',
                'lastLogin',
                'roles',
            ],
            hydraMember: \count(UserFixtures::DATA),
            hydraView: false,
        );
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testUserCollectionGetUserAuth(): void
    {
        $this->checkCollectionGet(
            client: $this->createClientWithCredentials(),
            totalItems: \count(UserFixtures::DATA),
            hasKey: [
                'fullName',
                'id',
                'username',
                'email',
                'lastLogin',
                'roles',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'confirmationToken',
                'passwordRequestedAt',
                'plainPassword',
                'groups',
                'enabled',
            ],
            hydraMember: \count(UserFixtures::DATA),
            hydraView: false,
        );
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testUserCollectionGetSuperAdminAuth(): void
    {
        $this->checkCollectionGet(
            client: $this->createClientWithCredentials(user: 'admin'),
            totalItems: \count(UserFixtures::DATA),
            hasKey: [
                'fullName',
                'id',
                'username',
                'email',
                'lastLogin',
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
            hydraMember: \count(UserFixtures::DATA),
            hydraView: false,
        );
    }
}