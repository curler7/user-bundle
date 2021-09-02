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
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
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
    public function testUserItemDeleteAuthUserOther(): void
    {
        $this->check403(
            $this->createClientWithCredentials(),
            ['username' => UserFixtures::DATA[1]['username']],
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
     * @throws TransportExceptionInterface
     * @throws RequestUrlNotFoundException
     */
    public function testUserItemDeleteAuthSuperAdmin(): void
    {
        $this->checkItemDelete($this->createClientWithCredentials(user: 'admin'));
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestUrlNotFoundException
     */
    public function testUserItemDeleteAuthSuperAdminValidatorLastSuperAdmin(): void
    {
        $this->checkItemDelete(
            $this->createClientWithCredentials(user: 'admin'),
            ['username' => UserFixtures::DATA[1]['username']],
        );
    }
}