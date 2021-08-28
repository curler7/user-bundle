<?php

declare(strict_types=1);

namespace Curler7\UserBundle\Model\AwareTrait;

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