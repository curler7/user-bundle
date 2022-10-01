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

use Curler7\UserBundle\Model\Aware\Interfaces\PlainPasswordAwareInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait PlainPasswordAwareTrait
{
    protected ?string $plainPassword = null;

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): PlainPasswordAwareInterface
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function eraseCredentials(): PlainPasswordAwareInterface
    {
        $this->plainPassword = null;

        return $this;
    }
}