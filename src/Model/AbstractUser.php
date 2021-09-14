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

use Curler7\UserBundle\Model\AwareTrait\ResourceAwareTrait;
use Curler7\UserBundle\Model\AwareTrait\RolesAwareTrait;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
abstract class AbstractUser implements UserInterface
{
    use ResourceAwareTrait,
        RolesAwareTrait;

    protected ?string $username = null;

    protected ?string $email = null;

    protected ?string $usernameCanonical = null;

    protected ?string $emailCanonical = null;

    protected ?string $password = null;

    protected bool $enabled = true;

    protected bool $verified = false;

    protected bool $share = false;

    protected ?\DateTimeInterface $lastLogin = null;

    protected ?\DateTimeInterface $loginLinkRequestedAt = null;

    protected ?string $plainPassword = null;

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

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    public function isShare(): bool
    {
        return $this->share;
    }

    public function setShare(bool $share): static
    {
        $this->share = $share;

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
        return $this->loginLinkRequestedAt;
    }

    public function setLoginLinkRequestedAt(?\DateTimeInterface $loginLinkRequestedAt): static
    {
        $this->loginLinkRequestedAt = $loginLinkRequestedAt;

        return $this;
    }

    public function getUserIdentifier(): ?string
    {
        return $this->username;
    }

    public function getSalt()
    {}
}
