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

namespace Curler7\UserBundle\Model\Aware\Interfaces;

use Curler7\UserBundle\Model\UserInterface;
use Doctrine\Common\Collections\Collection;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface UsersAwareInterface
{
    function getUsers(bool $asArray = true): array|Collection;
    function addUser(UserInterface $user, \Closure $p = null): static;
    function removeUser(UserInterface $user, \Closure $p = null): static;
}