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

use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait ResourceAwareTrait
{
    protected AbstractUid $id;

    public function __construct(?AbstractUid $id = null)
    {
        $this->id = $id ?? Uuid::v4();
    }

    public function getId(): AbstractUid
    {
        return $this->id;
    }
}