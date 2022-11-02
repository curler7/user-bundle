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

use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface ResourceAwareInterface
{
    const RESOURCE_AWARE_FILTER = [
        'id',
    ];

    public function getId(): AbstractUid;
}