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

use Curler7\UserBundle\Model\Aware\Interfaces\UsernameAwareInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait UsernameAwareTrait
{
    protected ?string $username = null;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): UsernameAwareInterface
    {
        $this->username = $username;

        return $this;
    }
}