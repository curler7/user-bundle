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

namespace Curler7\UserBundle\Tests\Api\Functional\User\AuthNoop;

use App\DataFixtures\UserFixtures;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\UserBundle\Tests\Api\Functional\User\AbstractUserResourceTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceCollectionGetAuthNoopTest extends AbstractUserResourceTest
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testUserCollectionGetAuthNoop(): void
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
                'loginLinkRequestedAt',
                'plainPassword',
                'groups',
                'enabled',
                'lastLogin',
                'roles',
                'verified',
                'share',
            ],
            hydraMember: \count(UserFixtures::DATA),
            hydraView: false,
        );
    }
}