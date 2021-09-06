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
use Curler7\UserBundle\Model\UserInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceItemDeleteTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];
    protected const GLOBAL_METHOD = self::METHOD_DELETE;

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testUserItemDeleteAuthNoop(): void
    {
        $this->check401(static::createClient());
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testUserItemDeleteAuthUserOtherUser(): void
    {
        $this->check403(
            $this->createClientWithCredentials(),
            ['username' => UserFixtures::DATA[2]['username']],
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testUserItemDeleteAuthUserOtherAdmin(): void
    {
        $this->check403(
            $this->createClientWithCredentials(),
            ['username' => UserFixtures::DATA[1]['username']],
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testUserItemDeleteAuthSuperAdminValidatorLastSuperAdmin(): void
    {
        $this->check422(
            client: $this->createClientWithCredentials(user: 'admin'),
            description: 'enabled: curler7_user.user.enabled.last_super_admin',
            criteria: ['username' => UserFixtures::DATA[1]['username']],
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws RequestUrlNotFoundException
     */
    public function testUserItemDeleteAuthUserSelf(): void
    {
        $this->checkItemDelete($this->createClientWithCredentials());
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestUrlNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testUserItemDeleteAuthSuperAdminSelf(): void
    {
        static::update(
            fn(UserInterface $user) => $user->setEnabled(true),
            ['username' => UserFixtures::DATA[3]['username']],
        );

        $this->checkItemDelete(
            client: $this->createClientWithCredentials(user: 'admin'),
            criteria: ['username' => UserFixtures::DATA[1]['username']],
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestUrlNotFoundException
     */
    public function testUserItemDeleteAuthSuperAdminOtherUser(): void
    {
        $this->checkItemDelete($this->createClientWithCredentials(user: 'admin'));
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestUrlNotFoundException
     */
    public function testUserItemDeleteAuthSuperAdminOtherAdmin(): void
    {
        $this->checkItemDelete(
            client: $this->createClientWithCredentials(user: 'admin'),
            criteria: ['username' => UserFixtures::DATA[3]['username']],
        );
    }
}