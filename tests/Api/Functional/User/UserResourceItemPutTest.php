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
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceItemPutTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];

    /**
     * @throws ArrayHasMoreItemsException
     * @throws RequestUrlNotFoundException
     * @throws ArrayNotEmptyException
     * @throws RedirectionExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testUserItemPutUserAuth(): void
    {
        $this->checkItemPut(
            client: $this->createClientWithCredentials(),
            json: [
                'email' => 'new@example.com',
            ],
            contains: [
                'username' => UserFixtures::DATA[0]['username'],
                'email' => 'new@example.com',
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
}