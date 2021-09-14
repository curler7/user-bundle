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
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
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
    protected const GLOBAL_METHOD = self::METHOD_POST;

    protected int $collectionPostResponseStatusCode = 200;

    protected array $collectionPostResponseHeaderSame = [];

    protected array $checkPropertiesHasKey = [];

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserCollectionLoginLinkAuthSuperAdminWithNoParameters(): void
    {
        $this->check422(
            client: self::createClient(),
            description: 'email: curler7_user.user.email.not_blank',
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserCollectionLoginLinkAuthSuperAdminWithFalseParameters(): void
    {
        $this->check422(
            client: self::createClient(),
            description: 'email: curler7_user.user.email.email',
            json: [
                'identifier' => 'a',
            ],
        );
    }

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
    public function testResourceUserCollectionLoginLinkAuthSuperAdminWithMinimalParameters(): void
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