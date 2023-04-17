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

use Curler7\UserBundle\Model\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait UserControlAwareTrait
{
    protected ?UserInterface $createdFrom = null;
    protected ?UserInterface $updatedFrom = null;

    public function __construct(?UserInterface $createdFrom = null)
    {
        $this->createdFrom = $createdFrom;
    }

    function getCreatedFrom(): ?UserInterface
    {
        return $this->createdFrom;
    }

    function setCreatedFrom(?UserInterface $createdFrom): static
    {
        $this->createdFrom = $createdFrom;

        return $this;
    }

    function getUpdatedFrom(): ?UserInterface
    {
        return $this->updatedFrom;
    }

    function setUpdatedFrom(?UserInterface $updatedFrom): static
    {
        $this->updatedFrom = $updatedFrom;

        return $this;
    }
}
