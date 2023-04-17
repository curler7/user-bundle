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
interface DateControlAwareInterface
{
    const DATE_CONTROL_AWARE_FILTER = [
        'createdAt',
        'updatedAt',
    ];

    function getCreatedAt(): \DateTimeInterface;
    function setCreatedAt(\DateTimeInterface $createdAt): static;

    function getUpdatedAt(): ?\DateTimeInterface;
    function setUpdatedAt(?\DateTimeInterface $updatedAt): static;
}
