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
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
use Curler7\UserBundle\Tests\Api\Functional\User\AbstractUserResourceTest;
use Curler7\ApiTestBundle\Exception\ResourceFoundException;
use Curler7\ApiTestBundle\Exception\ResourceNotFoundException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceItemDeleteAuthUserTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];
    protected const GLOBAL_METHOD = self::METHOD_DELETE;

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testResourceUserItemDeleteAuthUserOtherUser(): void
    {
        $this->check403(
            client: $this->createClientWithCredentials(),
            criteria: ['username' => UserFixtures::DATA[2]['username']],
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testResourceUserItemDeleteAuthUserOtherAdmin(): void
    {
        $this->check403(
            client: $this->createClientWithCredentials(),
            criteria: ['username' => UserFixtures::DATA[1]['username']],
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestUrlNotFoundException
     * @throws ResourceFoundException
     * @throws ResourceNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testResourceUserItemDeleteAuthUserSelf(): void
    {
        $this->checkItemDelete($this->createClientWithCredentials());
    }
}