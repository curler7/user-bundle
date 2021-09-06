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
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Curler7\UserBundle\Model\AbstractUser;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
#[ORM\Entity]
#[ORM\Table(name: 'app_user')]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'validation_groups' => ['Default', 'post'],
            'security_post_denormalize' => 'is_granted("POST", object)'
        ],
        'register' => [
            'method' => 'POST',
            'path' => '/users/register',
            'validation_groups' => ['Default', 'register'],
        ],
        'login_link' => [
            'method' => 'POST',
            'path' => '/users/login-link',
            'validation_groups' => ['Default', 'login_link'],
            'controller' => 'curler7_user.controller.login_link',
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
    order: ['username' => 'ASC'],
)]
class User extends AbstractUser
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ApiProperty(identifier: true)]
    protected AbstractUid $id;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:post', 'user:put', 'user:get'])]
    protected ?string $fullName = null;

    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'app_users_groups')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    protected Collection|array $groups;

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }
}
