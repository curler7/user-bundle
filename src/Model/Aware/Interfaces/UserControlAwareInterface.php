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

namespace Curler7\UserBundle\Model\Aware\Interfaces;

use Curler7\UserBundle\Model\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface UserControlAwareInterface
{
    const USER_CONTROL_AWARE_FILTER = [
        'createdFrom.username',
        'updatedFrom.username',
    ];

    function getCreatedFrom(): ?UserInterface;
    function setCreatedFrom(?UserInterface $user): self;

    function getUpdatedFrom(): ?UserInterface;
    function setUpdatedFrom(?UserInterface $user): self;
}
