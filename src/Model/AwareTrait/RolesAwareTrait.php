<?php

declare(strict_types=1);

namespace Curler7\UserBundle\Model\AwareTrait;

use Curler7\UserBundle\Model\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait RolesAwareTrait
{
    protected array $roles = [UserInterface::ROLE_DEFAULT];

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (method_exists($this, 'getGroups')) {
            foreach ($this->getGroups() as $group) {
                $roles = array_merge($roles, $group->getRoles());
            }

            $roles[] = UserInterface::ROLE_DEFAULT;
        }

        return array_unique($roles);
    }

    public function addRole(string $role): static
    {
        $role = strtoupper($role);

        if (!$this->hasRole($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return \in_array(strtoupper($role), $this->roles, true);
    }

    public function removeRole(string $role): static
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function setRoles(?array $roles): static
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }
}