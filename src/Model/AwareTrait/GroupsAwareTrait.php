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

namespace Curler7\UserBundle\Model\AwareTrait;

use Curler7\UserBundle\Model\GroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait GroupsAwareTrait
{
    /** @var GroupInterface[] */
    protected Collection|array $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function getGroups(bool $asArray = true): Collection|array
    {
        return $asArray ? $this->groups->toArray() : $this->groups;
    }

    public function getGroupNames(): array
    {
        $names = [];
        foreach ($this->getGroups() as $group) {
            $names[] = $group->getName();
        }

        return $names;
    }

    public function hasGroup(string|GroupInterface $group): bool
    {
        if ($group instanceof GroupInterface) {
            return $this->getGroups()->contains($group);
        }

        return \in_array($group, $this->getGroupNames(), true);
    }

    public function addGroup(GroupInterface $group, \Closure $p = null): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;

            !$p ?: $p();
        }

        return $this;
    }

    public function removeGroup(GroupInterface $group, \Closure $p = null): static
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);

            !$p ?: $p();
        }

        return $this;
    }
}
