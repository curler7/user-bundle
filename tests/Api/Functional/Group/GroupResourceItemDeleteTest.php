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

namespace Curler7\UserBundle\Tests\Api\Functional\Group;

use App\DataFixtures\GroupFixtures;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Curler7\ApiTestBundle\Exception\RequestUrlNotFoundException;
use Curler7\ApiTestBundle\Exception\ResourceFoundException;
use Curler7\ApiTestBundle\Exception\ResourceNotFoundException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class GroupResourceItemDeleteTest extends AbstractGroupResourceTest
{
    protected const GLOBAL_METHOD = self::METHOD_DELETE;
    protected const GLOBAL_CRITERIA = ['name' => GroupFixtures::DATA[0]['name']];

    /**
     * @throws ConstraintNotDefinedException
     * @throws TransportExceptionInterface
     * @throws RequestMethodNotFoundException
     */
    public function testGroupItemDeleteAuthNoop(): void
    {
        $this->check401(self::createClient());
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testGroupItemDeleteAuthUser(): void
    {
        $this->check403($this->createClientWithCredentials());
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testGroupItemDeleteAuthSuperAdminForeignKeyWithUser(): void
    {
        $this->check500(client: $this->createClientWithCredentials(user: 'admin'));
    }

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestUrlNotFoundException
     * @throws ResourceFoundException
     * @throws ResourceNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testGroupItemDeleteAuthSuperAdmin(): void
    {
        $this->checkItemDelete(
            client: $this->createClientWithCredentials(user: 'admin'),
            criteria: ['name' => GroupFixtures::DATA[1]['name']],
        );
    }
}