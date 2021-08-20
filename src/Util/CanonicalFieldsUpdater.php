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

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class CanonicalFieldsUpdater implements CanonicalFieldsUpdaterInterface
{
    public function __construct(
        private CanonicalizerInterface $usernameCanonicalizer,
        private CanonicalizerInterface $emailCanonicalizer
    ) {}

    public function updateCanonicalFields(UserInterface $user): static
    {
        $user->setUsernameCanonical($this->usernameCanonicalizer->canonicalize($user->getUsername()));
        $user->setEmailCanonical($this->emailCanonicalizer->canonicalize($user->getEmail()));

        return $this;
    }

    public function canonicalizeEmail(string $email): string
    {
        return $this->emailCanonicalizer->canonicalize($email);
    }

    public function canonicalizeUsername(string $username): string
    {
        return $this->usernameCanonicalizer->canonicalize($username);
    }
}
