<?php

declare(strict_types=1);

namespace Curler7\UserBundle\Util;

use Curler7\UserBundle\Model\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface UserSpyInterface
{
    public function spy(UserInterface $user, array $data, array $context): self;
}