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

namespace Curler7\UserBundle\Model;

use Curler7\UserBundle\Model\AwareTrait\ResourceAwareTrait;
use Curler7\UserBundle\Model\AwareTrait\RolesAwareTrait;
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
abstract class AbstractGroup implements GroupInterface
{
    use ResourceAwareTrait,
        RolesAwareTrait {
        ResourceAwareTrait::__construct as protected __constructResource;
    }

    public function __construct(
        protected string $name,
        ?AbstractUid $id = null,
    ) {
        $this->__constructResource($id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
