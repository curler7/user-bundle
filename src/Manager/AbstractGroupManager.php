<?php

/*
 * This file is part of the Curler7UserBundle project.
 *
 * (c) Gunnar Suwe <suwe@smart-media.design>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Curler7\UserBundle\Manager;

use Curler7\UserBundle\Model\GroupInterface;
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
abstract class AbstractGroupManager implements GroupManagerInterface
{
    public function createGroup(string $name, array $roles = [], ?AbstractUid $id = null): GroupInterface
    {
        /** @var GroupInterface $group */
        $class = $this->getClass();
        $group = new $class($name, $id);

        return $group
            ->setRoles($roles)
        ;
    }

    public function findGroupByName(string $name): ?GroupInterface
    {
        return $this->findGroupBy(['name' => $name]);
    }
}
