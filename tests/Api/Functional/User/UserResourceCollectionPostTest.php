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

use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceCollectionPostTest extends AbstractUserResourceTest
{
    protected const GLOBAL_METHOD = self::METHOD_POST;

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testUserCollectionPostAuthNoop(): void
    {
        $this->check401(static::createClient());
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testUserCollectionPostAuthUser(): void
    {
        $this->check403($this->createClientWithCredentials());
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
    public function testUserCollectionPostAuthSuperAdmin(): void
    {
        $this->checkCollectionPost(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                'fullName' => 'new',
                'username' => 'new',
                'email' => 'corona@smart-media.design',
                'password' => 'pass',
            ],
            contains: [
                'username' => 'new',
                'email' => 'corona@smart-media.design',
            ],
            hasKey: [
                'fullName',
                'id',
                'username',
                'email',
                'groups',
                'enabled',
                'lastLogin',
                'roles',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'loginLinkRequestedAt',
                'plainPassword',
                'password',
            ],
        );
    }
}