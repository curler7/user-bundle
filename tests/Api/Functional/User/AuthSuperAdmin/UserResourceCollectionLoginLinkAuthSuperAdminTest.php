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

namespace Curler7\UserBundle\Tests\Api\Functional\User\AuthSuperAdmin;

use App\DataFixtures\UserFixtures;
use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
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
class UserResourceCollectionLoginLinkAuthSuperAdminTest extends AbstractUserResourceTest
{
    protected const URI = '/users/login-link';

    protected int $collectionPostResponseStatusCode = 200;

    protected array $collectionPostResponseHeaderSame = [];

    protected array $checkPropertiesHasKey = [];

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
     */
    public function testUserCollectionLoginLink(): void
    {
        $this->checkCollectionPost(
            client: static::createClient(),
            json: [
                'identifier' => UserFixtures::DATA[0]['email'],
            ],
            contains: [],
            notHasKey: [
                'id',
                'email',
                'fullName',
                'username',
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'groups',
                'enabled',
                'lastLogin',
                'roles',
                'verified',
                'share',
            ],
            jsonContains: false,
        );
    }
}