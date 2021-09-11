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

use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Curler7\UserBundle\Tests\Api\Functional\User\AbstractUserResourceTest;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceCollectionPostAuthUserTest extends AbstractUserResourceTest
{
    protected const GLOBAL_METHOD = self::METHOD_POST;

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testUserCollectionPostAuthUser(): void
    {
        $this->check403($this->createClientWithCredentials());
    }
}