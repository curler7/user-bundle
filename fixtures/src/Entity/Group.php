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

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Curler7\UserBundle\Model\AbstractGroup;
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
#[ORM\Entity]
#[ORM\Table(name: 'app_group')]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'validation_groups' => ['Default', 'post'],
            'security_post_denormalize' => 'is_granted("POST", object)'
        ],
    ],
    itemOperations: [
        'get' => [
            'security' => 'is_granted("GET", object)'
        ],
        'put' => [
            'validation_groups' => ['Default', 'put'],
            'security' => 'is_granted("PUT", object)'
        ],
        'delete' => [
            'validation_groups' => ['delete'],
            'security' => 'is_granted("DELETE", object)'
        ],
    ],
    order: ['name' => 'ASC'],
)]
class Group extends AbstractGroup
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ApiProperty(identifier: true)]
    protected AbstractUid $id;
}
