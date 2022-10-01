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

namespace Curler7\UserBundle\Model\Aware\Traits;

use Curler7\UserBundle\Model\Aware\Interfaces\EnabledAwareInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait EnabledAwareTrait
{
    protected bool $enabled = false;

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): EnabledAwareInterface
    {
        $this->enabled = $enabled;

        return $this;
    }
}