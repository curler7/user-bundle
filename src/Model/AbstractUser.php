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

use Curler7\UserBundle\Model\AwareTrait\GroupsAwareTrait;
use Curler7\UserBundle\Model\AwareTrait\ResourceAwareTrait;
use Curler7\UserBundle\Model\AwareTrait\RolesAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
abstract class AbstractUser implements UserInterface
{
    use ResourceAwareTrait,
        RolesAwareTrait,
        GroupsAwareTrait {
        ResourceAwareTrait::__construct as protected __constructResource;
        GroupsAwareTrait::__construct as protected __constructGroups;
    }

    protected ?string $username = null;

    protected ?string $email = null;

    protected ?string $usernameCanonical = null;

    protected ?string $emailCanonical = null;

    protected ?string $password = null;

    protected bool $enabled = false;

    protected ?\DateTimeInterface $lastLogin = null;

    protected ?\DateTimeInterface $loginLinkRequestedAt = null;

    protected ?string $plainPassword = null;

    protected ?\DateTimeInterface $passwordRequestedAt = null;

    public function __construct(?AbstractUid $id = null)
    {
        $this->__constructResource($id);
        $this->__constructGroups();
    }

    public function eraseCredentials(): static
    {
        $this->plainPassword = null;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getUsernameCanonical(): ?string
    {
        return $this->usernameCanonical;
    }

    public function setUsernameCanonical(?string $usernameCanonical): static
    {
        $this->usernameCanonical = $usernameCanonical;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getEmailCanonical(): ?string
    {
        return $this->emailCanonical;
    }

    public function setEmailCanonical(?string $emailCanonical): static
    {
        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getLoginLinkRequestedAt(): ?\DateTimeInterface
    {
        return $this->passwordRequestedAt;
    }

    public function setLoginLinkRequestedAt(?\DateTimeInterface $passwordRequestedAt): static
    {
        $this->passwordRequestedAt = $passwordRequestedAt;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getSalt()
    {}
}
