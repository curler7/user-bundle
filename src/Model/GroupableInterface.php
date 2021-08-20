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

namespace Curler7\UserBundle\Model;

use Doctrine\Common\Collections\Collection;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface GroupableInterface
{
    public function getGroups(bool $asArray = true): Collection|array;

    public function getGroupNames(): array;

    public function hasGroup(string $group): bool;

    public function addGroup(GroupInterface $group): static;

    public function removeGroup(GroupInterface $group): static;
}
