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

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface PlainPasswordAwareInterface
{
    const PLAIN_PASSWORD_AWARE_FILTER = [
        'plainPassword',
    ];

    function getPlainPassword(): ?string;
    function setPlainPassword(?string $plainPassword): self;
    function eraseCredentials(): self;
}