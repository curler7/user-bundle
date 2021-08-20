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

use Ramsey\Uuid\UuidInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface GroupInterface
{
    public function addRole(string $role): static;

    public function getId(): UuidInterface;

    public function getName(): string;

    public function hasRole(string $role): bool;

    public function getRoles(): array;

    public function removeRole(string $role): static;

    public function setName(string $name): static;

    public function setRoles(array $roles): static;
}
