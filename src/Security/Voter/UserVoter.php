<?php

/*
 * This file is part of the Curler7ApiTestBundle project.
 *
 * (c) Gunnar Suwe <suwe@smart-media.design>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Curler7\UserBundle\Security\Voter;

use Curler7\UserBundle\Model\UserInterface as ModelUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserVoter extends AbstractVoter
{
    protected const RESOURCE = UserInterface::class;

    protected function checkPost(?UserInterface $user, UserInterface $subject): bool
    {
        return $this->security->isGranted(ModelUserInterface::ROLE_SUPER_ADMIN);
    }

    protected function checkGet(?UserInterface $user, UserInterface $subject): bool
    {
        return $this->security->isGranted(ModelUserInterface::IS_AUTHENTICATED_FULLY);
    }

    protected function checkPut(?UserInterface $user, UserInterface $subject): bool
    {
        return $user === $subject || $this->security->isGranted(ModelUserInterface::ROLE_SUPER_ADMIN);
    }

    protected function checkDelete(?UserInterface $user, UserInterface $subject): bool
    {
        return $user === $subject || $this->security->isGranted(ModelUserInterface::ROLE_SUPER_ADMIN);
    }

    protected function checkRegister(?UserInterface $user, UserInterface $subject): bool
    {
        return $this->security->isGranted(ModelUserInterface::IS_ANONYMOUS);
    }

    protected function checkLoginLink(?UserInterface $user, UserInterface $subject): bool
    {
        return $this->security->isGranted(ModelUserInterface::IS_AUTHENTICATED_ANONYMOUSLY);
    }
}