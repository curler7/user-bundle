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

namespace Curler7\UserBundle\Manager;

use Curler7\UserBundle\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface UserManagerInterface
{
    public function createUser(): UserInterface;

    public function deleteUser(UserInterface $user): self;

    public function findUserBy(array $criteria): ?UserInterface;

    public function findUserByUsername(string $username): ?UserInterface;

    public function findUserByEmail(string $email): ?UserInterface;

    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?UserInterface;

    public function findUserByConfirmationToken(string $token): UserInterface;

    public function findUsers(): array;

    public function getClass(): string;

    public function reloadUser(UserInterface $user): self;

    public function updateUser(UserInterface $user, $flush = true): self;

    public function updateCanonicalFields(UserInterface $user): self;

    public function updatePassword(UserInterface $user): self;
}
