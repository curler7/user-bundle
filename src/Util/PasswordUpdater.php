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

namespace Curler7\UserBundle\Util;

use Curler7\UserBundle\Model\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class PasswordUpdater implements PasswordUpdaterInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function hashPassword(UserInterface $user): static
    {
        if (!$user->getPlainPassword()) {
            return $this;
        }

        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            $user->getPlainPassword()
        ));
        $user->eraseCredentials();

        return $this;
    }
}
