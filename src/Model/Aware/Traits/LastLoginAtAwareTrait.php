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

use Curler7\UserBundle\Model\Aware\Interfaces\LastLoginAtAwareInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait LastLoginAtAwareTrait
{
    protected ?\DateTimeInterface $lastLoginAt = null;

    public function getLastLoginAt(): ?\DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTimeInterface $lastLoginAt): LastLoginAtAwareInterface
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }
}