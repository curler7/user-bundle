<?php

declare(strict_types=1);

namespace Curler7\UserBundle\Model\AwareTrait;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
trait ResourceAwareTrait
{
    protected UuidInterface $id;

    public function __construct(?UuidInterface $id = null)
    {
        $this->id = $id ?? Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}