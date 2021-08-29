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
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourceItemDeleteTest extends AbstractUserResourceTest
{
    protected const GLOBAL_CRITERIA = ['username' => UserFixtures::DATA[0]['username']];

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestUrlNotFoundException
     */
    public function testUserItemDeleteWithAdminAuth(): void
    {
        $this->checkItemDelete(client: $this->createClientWithCredentials(user: 'admin'));
    }
}