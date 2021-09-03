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

use Curler7\UserBundle\Model\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class GroupVoter extends AbstractVoter
{
    protected const RESOURCE = UserInterface::class;

    protected function checkPost(UserInterface $user, UserInterface $subject): bool
    {
        return $this->security->isGranted(UserInterface::ROLE_SUPER_ADMIN);
    }

    protected function checkGet(UserInterface $user, UserInterface $subject): bool
    {
        return $this->security->isGranted(UserInterface::ROLE_SUPER_ADMIN);
    }

    protected function checkPut(UserInterface $user, UserInterface $subject): bool
    {
        return $this->security->isGranted(UserInterface::ROLE_SUPER_ADMIN);
    }

    protected function checkDelete(UserInterface $user, UserInterface $subject): bool
    {
        return $this->security->isGranted(UserInterface::ROLE_SUPER_ADMIN);
    }
}