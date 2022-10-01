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

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface RolesAwareInterface
{
    const ROLES_AWARE_FILTER = [
        'roles',
    ];

    function getRoles(): array;
    function addRole(string $role): self;
    function hasRole(string $role): bool;
    function removeRole(string $role): self;
    function setRoles(?array $roles): self;
}