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
use App\Entity\User;
use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
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
class UserResourceItemPutAuthSuperAdminTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];
    protected const GLOBAL_METHOD = self::METHOD_PUT;

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testResourceUserItemPutAuthSuperAdminValidatorLastSuperAdmin(): void
    {
        $this->check422(
            client: $this->createClientWithCredentials(user: 'admin'),
            description: 'enabled: curler7_user.user.enabled.last_super_admin',
            criteria: [
                'username' => UserFixtures::DATA[1]['username'],
            ],
            json: [
                'enabled' => false,
            ],
        );
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserItemPutAuthSuperAdminSelfWithFalseParameters(): void
    {
        $this->check422(
            client: $this->createClientWithCredentials(user: 'admin'),
            description: 'username: curler7_user.user.username.length.min
email: curler7_user.user.email.email
password: curler7_user.user.plain_password.length.min',
            criteria: [
                'username' => UserFixtures::DATA[1]['username'],
            ],
            json: [
                'username' => 'a',
                'email' => 'a',
                'password' => 'a',
            ],
        );
    }

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
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemPutAuthSuperAdminSelfWithMinimalParameters(): void
    {
        /** @var User $user */
        $user = self::findOneBy(['username' => UserFixtures::DATA[1]['username']]);

        $this->checkItemPut(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [],
            contains: [
                'username' => UserFixtures::DATA[1]['username'],
                'email' => UserFixtures::DATA[1]['email'],
                'id' => $user->getId()->toRfc4122(),
                'fullName' => UserFixtures::DATA[1]['fullName'],
                'lastLogin' => (new \DateTime())->format(DATE_W3C),
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN,
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => UserFixtures::DATA[1]['enabled'],
                'verified' => UserFixtures::DATA[1]['verified'],
            ],
            criteria: [
                'username' => UserFixtures::DATA[1]['username']
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'share',
            ],
        );
    }

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
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemPutAuthSuperAdminSelfWithAllResourceParameters(): void
    {
        /** @var User $user */
        $user = self::findOneBy(['username' => UserFixtures::DATA[1]['username']]);

        static::update(
            fn(UserInterface $user) => $user->setEnabled(true),
            ['username' => UserFixtures::DATA[3]['username']],
        );

        $this->checkItemPut(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                'username' => 'put user',
                'email' => 'new@example.com',
                'id' => UuidV4::v4(),
                'fullName' => 'put user',
                'lastLogin' => (new \DateTime('+2 years'))->format(DATE_W3C),
                'roles' => [
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => false,
                'verified' => false,
                'usernameCanonical' => null,
                'emailCanonical' => null,
                'password' => 'password',
                'loginLinkRequestedAt' => (new \DateTime('+2 years'))->format(DATE_W3C),
                // 'plainPassword',
                'share' => true,
            ],
            contains: [
                'username' => 'put user',
                'email' => 'new@example.com',
                'id' => $user->getId()->toRfc4122(),
                'fullName' => 'put user',
                'lastLogin' => (new \DateTime())->format(DATE_W3C),
                'roles' => [
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => false,
                'verified' => true,
            ],
            criteria: [
                'username' => UserFixtures::DATA[1]['username']
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'share',
            ],
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserItemPutAuthSuperAdminOtherUserWithFalseParameters(): void
    {
        $this->check422(
            client: $this->createClientWithCredentials(user: 'admin'),
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
     * @throws RequestUrlNotFoundException
     * @throws ArrayNotEmptyException
     * @throws RedirectionExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws PropertyNotCheckedException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemPutAuthSuperAdminOtherUserWithMinimalParameters(): void
    {
        /** @var User $user */
        $user = self::findOneBy();

        $this->checkItemPut(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [],
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'fullName' => UserFixtures::DATA[0]['fullName'],
                'lastLogin' => null,
                'username' => UserFixtures::DATA[0]['username'],
                'email' => UserFixtures::DATA[0]['email'],
                'roles' => [
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => UserFixtures::DATA[0]['enabled'],
                'verified' => UserFixtures::DATA[0]['verified'],
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'share',
            ],
        );
    }

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
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemPutAuthSuperAdminOtherUserWithAllResourceParameters(): void
    {
        /** @var User $user */
        $user = self::findOneBy();

        $this->checkItemPut(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                'id' => UuidV4::v4()->toRfc4122(),
                'fullName' => 'put user',
                'lastLogin' => (new \DateTime('+2 years'))->format(DATE_W3C),
                'username' => 'put user',
                'email' => 'put@example.com',
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN,
                ],
                'enabled' => false,
                'verified' => false,
                'usernameCanonical' => null,
                'emailCanonical' => null,
                'password' => 'password',
                'loginLinkRequestedAt' => (new \DateTime('+2 years'))->format(DATE_W3C),
                // 'plainPassword',
                'share' => true,
            ],
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'fullName' => 'put user',
                'lastLogin' => null,
                'username' => 'put user',
                'email' => 'put@example.com',
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN,
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => false,
                'verified' => true,
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'share',
            ],
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     */
    public function testResourceUserItemPutAuthSuperAdminOtherAdminWithFalseParameters(): void
    {
        $this->check422(
            client: $this->createClientWithCredentials(user: 'admin'),
            description: 'username: curler7_user.user.username.length.min
email: curler7_user.user.email.email
password: curler7_user.user.plain_password.length.min',
            criteria: [
                'username' => UserFixtures::DATA[3]['username']
            ],
            json: [
                'username' => 'a',
                'email' => 'a',
                'password' => 'a',
            ],
        );
    }

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
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemPutAuthSuperAdminOtherAdminWithMinimalParameters(): void
    {
        /** @var User $user */
        $user = self::findOneBy(['username' => UserFixtures::DATA[3]['username']]);

        $this->checkItemPut(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [],
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'fullName' => UserFixtures::DATA[3]['fullName'],
                'lastLogin' => null,
                'username' => UserFixtures::DATA[3]['username'],
                'email' => UserFixtures::DATA[3]['email'],
                'roles' => [
                    UserInterface::ROLE_SUPER_ADMIN,
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => UserFixtures::DATA[3]['enabled'],
                'verified' => UserFixtures::DATA[3]['verified'],
            ],
            criteria: [
                'username' => UserFixtures::DATA[3]['username']
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'share',
            ],
        );
    }

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
     * @throws PropertyCheckedToManyCanNullKeyException
     */
    public function testResourceUserItemPutAuthSuperAdminOtherAdminWithAllResourceParameters(): void
    {
        /** @var User $user */
        $user = self::findOneBy(['username' => UserFixtures::DATA[3]['username']]);

        $this->checkItemPut(
            client: $this->createClientWithCredentials(user: 'admin'),
            json: [
                'id' => UuidV4::v4()->toRfc4122(),
                'fullName' => 'put user',
                'lastLogin' => (new \DateTime('+2 years'))->format(DATE_W3C),
                'username' => 'put user',
                'email' => 'put@example.com',
                'roles' => [],
                'enabled' => true,
                'verified' => true,
                'usernameCanonical' => null,
                'emailCanonical' => null,
                'password' => 'password',
                'loginLinkRequestedAt' => (new \DateTime('+2 years'))->format(DATE_W3C),
                // 'plainPassword',
                'share' => true,
            ],
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'fullName' => 'put user',
                'lastLogin' => null,
                'username' => 'put user',
                'email' => 'put@example.com',
                'roles' => [
                    UserInterface::ROLE_DEFAULT,
                ],
                'enabled' => true,
                'verified' => false,
            ],
            criteria: [
                'username' => UserFixtures::DATA[3]['username']
            ],
            hasKey: [
                'id',
                'fullName',
                'lastLogin',
                'username',
                'email',
                'roles',
                'enabled',
                'verified',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'loginLinkRequestedAt',
                'plainPassword',
                'share',
            ],
        );
    }
}