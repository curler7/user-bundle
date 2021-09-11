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

namespace Curler7\UserBundle\Tests\Api\Functional\Group\AuthUser;

use App\DataFixtures\GroupFixtures;
use Curler7\ApiTestBundle\Exception\ConstraintNotDefinedException;
use Curler7\ApiTestBundle\Exception\RequestMethodNotFoundException;
use Curler7\UserBundle\Tests\Api\Functional\Group\AbstractGroupResourceTest;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class GroupResourceItemGetAuthUserTest extends AbstractGroupResourceTest
{
    protected const GLOBAL_METHOD = self::METHOD_GET;
    protected const GLOBAL_CRITERIA = ['name' => GroupFixtures::DATA[0]['name']];

    /**
     * @throws ConstraintNotDefinedException
     * @throws RequestMethodNotFoundException
     * @throws TransportExceptionInterface
     */
    public function testGroupItemGetAuthUser(): void
    {
        $this->check403($this->createClientWithCredentials());
    }
}