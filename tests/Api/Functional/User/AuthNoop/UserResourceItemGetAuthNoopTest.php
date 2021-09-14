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
use App\Entity\User;
use Curler7\ApiTestBundle\Exception\ArrayHasMoreItemsException;
use Curler7\ApiTestBundle\Exception\ArrayNotEmptyException;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\PropertyCheckedToManyCanNullKeyException;
use Curler7\ApiTestBundle\Exception\PropertyNotCheckedException;
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
use Curler7\UserBundle\Tests\Api\Functional\User\AbstractUserResourceTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceItemGetAuthNoopTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];
    protected const GLOBAL_METHOD = self::METHOD_GET;

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws ArrayHasMoreItemsException
     * @throws ArrayNotEmptyException
     * @throws PropertyCheckedToManyCanNullKeyException
     * @throws PropertyNotCheckedException
     * @throws RequestUrlNotFoundException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testResourceUserItemGetAuthNoop(): void
    {
        /** @var User $user */
        $user = self::findOneBy();

        $this->checkItemGet(
            $this->createClient(),
            contains: [
                'id' => $user->getId()->toRfc4122(),
                'username' => UserFixtures::DATA[0]['username'],
                'fullName' => UserFixtures::DATA[0]['fullName'],
            ],
            hasKey: [
                'id',
                'fullName',
                'username',
            ],
            notHasKey: [
                'usernameCanonical',
                'emailCanonical',
                'password',
                'plainPassword',
                'enabled',
                'verified',
                'share',
                'lastLogin',
                'loginLinkRequestedAt',
                'roles',
                'email',
            ],
        );
    }
}