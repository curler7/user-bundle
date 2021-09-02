<?php

declare(strict_types=1);

namespace Curler7\UserBundle\Util;

use Curler7\UserBundle\Model\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface UserRegistrationInterface
{
    public function register(UserInterface $user): static;
}