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

namespace Curler7\UserBundle\Tests\Api\Functional\Group\AuthSuperAdmin;

use App\DataFixtures\GroupFixtures;
use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\UserBundle\Tests\Api\Functional\Group\AbstractGroupResourceTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class GroupResourceCollectionPostAuthSuperAdminTest extends AbstractGroupResourceTest
{
    protected const GLOBAL_METHOD = self::METHOD_POST;

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws ArrayHasMoreItemsException
     * @throws ArrayNotEmptyException
     * @throws PropertyCheckedToManyCanNullKeyException
     * @throws PropertyNotCheckedException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGroupCollectionPostAuthSuperAdmin(): void
    {
        $this->checkCollectionPost(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                'name' => 'flip',
                'roles' => ['ROLE_FLIPPER'],
            ],
            contains: [
                'name' => 'flip',
                'roles' => ['ROLE_FLIPPER'],
            ],
            hasKey: [
                'id',
                'name',
                'roles',
            ],
        );
    }
}