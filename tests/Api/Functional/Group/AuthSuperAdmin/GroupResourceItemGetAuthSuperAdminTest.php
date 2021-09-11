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
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
use Curler7\UserBundle\Tests\Api\Functional\Group\AbstractGroupResourceTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class GroupResourceItemGetAuthSuperAdminTest extends AbstractGroupResourceTest
{
    protected const GLOBAL_METHOD = self::METHOD_GET;
    protected const GLOBAL_CRITERIA = ['name' => GroupFixtures::DATA[0]['name']];

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
     * @throws PropertyCheckedToManyCanNullKeyException
     * @throws RequestUrlNotFoundException
     */
    public function testGroupItemGetAuthSuperAdmin(): void
    {
        $this->checkItemGet(
            client: $this->createClientWithCredentials(user: 'admin'),
            contains: [
                'name' => GroupFixtures::DATA[0]['name'],
            ],
            hasKey: [
                'id',
                'name',
                'roles',
            ],
        );
    }
}