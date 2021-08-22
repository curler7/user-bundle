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

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Curler7\UserBundle\Model\AbstractGroup;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
#[ORM\Entity]
#[ORM\Table(name: 'app_group')]
class Group extends AbstractGroup
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;
}
