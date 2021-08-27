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
use Ramsey\Uuid\UuidInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface GroupManagerInterface
{
    public function createGroup(string $name, array $roles = [], ?UuidInterface $id = null): GroupInterface;

    public function deleteGroup(GroupInterface $group): static;

    public function findGroupBy(array $criteria): ?GroupInterface;

    public function findGroupByName(string $name): ?GroupInterface;

    public function findGroups(): array;

    public function getClass(): string;

    public function updateGroup(GroupInterface $group): static;
}
