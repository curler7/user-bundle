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

use Curler7\UserBundle\Model\Aware\Interfaces\VerifiedAwareInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait VerifiedAwareTrait
{
    protected bool $verified = false;

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): VerifiedAwareInterface
    {
        $this->verified = $verified;

        return $this;
    }
}