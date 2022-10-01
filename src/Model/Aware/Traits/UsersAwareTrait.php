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

namespace Curler7\UserBundle\Model\Aware\Traits;

use Curler7\UserBundle\Model\Aware\Interfaces\UsersAwareInterface;
use Curler7\UserBundle\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait UsersAwareTrait
{
    protected Collection|array $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getUsers(bool $asArray = true): array|Collection
    {
        return $asArray ? $this->users->toArray() : $this->users;
    }

    public function addUser(UserInterface $user, \Closure $p = null): UsersAwareInterface
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;

            !$p ?: $p();
        }

        return $this;
    }

    public function removeUser(UserInterface $user, \Closure $p = null): UsersAwareInterface
    {
        if ($this->users->contains($user)) {
            $this->users[] = $user;

            !$p ?: $p();
        }

        return $this;
    }
}
