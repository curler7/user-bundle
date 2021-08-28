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

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface UserInterface extends PasswordAuthenticatedUserInterface, GroupableInterface, BaseUserInterface
{
    public const ROLE_DEFAULT = 'ROLE_USER';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public function getId(): AbstractUid;

    public function addRole(string $role): static;

    public function hasRole(string $role): bool;

    public function removeRole(string $role): static;

    public function setRoles(?array $roles): static;

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): static;

    public function setUsername(?string $username): static;

    public function getUsernameCanonical(): ?string;

    public function setUsernameCanonical(?string $usernameCanonical): static;

    public function getEmail(): ?string;

    public function setEmail(?string $email): static;

    public function getEmailCanonical(): ?string;

    public function setEmailCanonical(?string $emailCanonical): static;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $plainPassword): static;

    public function setPassword(?string $password): static;

    public function getLastLogin(): ?\DateTimeInterface;

    public function setLastLogin(?\DateTimeInterface $lastLogin);

    public function getConfirmationToken(): ?string;

    public function setConfirmationToken(?string $confirmationToken): static;

    public function getPasswordRequestedAt(): ?\DateTimeInterface;

    public function setPasswordRequestedAt(?\DateTimeInterface $passwordRequestedAt): static;
}
