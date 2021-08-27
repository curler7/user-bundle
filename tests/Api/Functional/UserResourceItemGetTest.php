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

namespace Curler7\UserBundle\Tests\Api\Functional;

use App\DataFixtures\UserFixtures;
use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
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
     */
    public function testUserItemGet(): void
    {
        $client = static::createClient();

        $this->checkItemGet(
            client: $client,
            criteria: [
                'username' => UserFixtures::DATA[0]['username']
            ],
            contains: [
                'fullName' => UserFixtures::DATA[0]['full_name'],
                'username' => UserFixtures::DATA[0]['username'],
                'email' => UserFixtures::DATA[0]['email'],
                'enabled' => UserFixtures::DATA[0]['enabled'],
            ],
            hasKey: [
                'id',
                'fullName',
                'usernameCanonical',
                'emailCanonical',
                'password',
                'lastLogin',
                'confirmationToken',
                'passwordRequestedAt',
                'plainPassword',
                'username',
                'email',
                'enabled',
                'roles',
                'groups',
            ],
        );
    }
}