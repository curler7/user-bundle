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
use Curler7\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Curler7\UserBundle\Util\PasswordUpdaterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
abstract class AbstractUserManager implements UserManagerInterface
{
    public function __construct(
        private PasswordUpdaterInterface $passwordUpdater,
        private CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater
    ) {}

    public function createUser(): UserInterface
    {
        return new ($this->getClass())();
    }

    public function findUserByEmail(string $email): ?UserInterface
    {
        return $this->findUserBy(['emailCanonical' => $this->canonicalFieldsUpdater->canonicalizeEmail($email)]);
    }

    public function findUserByUsername(string $username): ?UserInterface
    {
        return $this->findUserBy(['usernameCanonical' => $this->canonicalFieldsUpdater->canonicalizeUsername($username)]);
    }

    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?UserInterface
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            if ($user = $this->findUserByEmail($usernameOrEmail)) {
                return $user;
            }
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    public function findUserByConfirmationToken(string $token): UserInterface
    {
        return $this->findUserBy(['confirmationToken' => $token]);
    }

    public function updateCanonicalFields(UserInterface $user): static
    {
        $this->canonicalFieldsUpdater->updateCanonicalFields($user);

        return $this;
    }

    public function updatePassword(UserInterface $user): static
    {
        $this->passwordUpdater->hashPassword($user);

        return $this;
    }
}
