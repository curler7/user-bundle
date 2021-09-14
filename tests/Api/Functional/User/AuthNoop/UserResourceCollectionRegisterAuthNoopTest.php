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

use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Curler7\UserBundle\Model\UserInterface;
use Curler7\UserBundle\Tests\Api\Functional\User\AbstractUserResourceTest;
use Symfony\Component\Uid\UuidV4;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceCollectionRegisterAuthNoopTest extends AbstractUserResourceTest
{
    protected const URI = '/users/register';
    protected const GLOBAL_METHOD = self::METHOD_POST;

    /**
     * @throws TransportExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserCollectionRegisterAuthNoopWithNoParameters(): void
    {
        $this->check422(
            client: self::createClient(),
            description: 'username: curler7_user.user.username.not_blank
email: curler7_user.user.email.not_blank
password: curler7_user.user.plain_password.not_blank',
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserCollectionRegisterAuthNoopWithFalseParameters(): void
    {
        $this->check422(
            client: self::createClient(),
            description: 'username: curler7_user.user.username.length.min
email: curler7_user.user.email.email
password: curler7_user.user.plain_password.length.min',
            json: [
                'username' => 'a',
                'email' => 'a',
                'password' => 'a',
            ],
        );
    }

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
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserCollectionRegisterAuthNoopWithMinimalParameters(): void
    {
        $this->checkCollectionPost(
            client: static::createClient(),
            json: [
                'username' => 'new',
                'email' => 'corona@smart-media.design',
                'password' => 'password',
            ],
            contains: [
                'username' => 'new',
                'email' => 'corona@smart-media.design',
            ],
            hasKey: [
                'id',
                'username',
                'email',
            ],
            notHasKey: [
                'fullName',
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
        );
    }

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
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserCollectionRegisterAuthNoopWithAllResourceParameters(): void
    {
        $uuid = UuidV4::v4()->toRfc4122();

        $this->checkCollectionPost(
            client: static::createClient(),
            json: [
                // 'id' => $uuid,
                'username' => 'new',
                'email' => 'corona@smart-media.design',
                'password' => 'password',
                'fullName' => 'register user',
                'usernameCanonical' => null,
                'emailCanonical' => null,
                'loginLinkRequestedAt' => (new \DateTime('+2 years'))->format(DATE_W3C),
                // 'plainPassword',
                'enabled' => false,
                'lastLogin' => (new \DateTime('+2 years'))->format(DATE_W3C),
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN,
                ],
                'verified' => true,
                'share' => true,
            ],
            contains: [
                // 'id' => $uuid,
                'username' => 'new',
                'email' => 'corona@smart-media.design',
            ],
            hasKey: [
                'id',
                'username',
                'email',
            ],
            notHasKey: [
                'fullName',
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
        );
    }
}