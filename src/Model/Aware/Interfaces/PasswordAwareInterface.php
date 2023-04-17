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

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface PasswordAwareInterface extends PasswordAuthenticatedUserInterface
{
    const PASSWORD_AWARE_FILTER = [
        'password',
    ];

    function setPassword(?string $password): static;
}