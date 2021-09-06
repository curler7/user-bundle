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
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
#[ORM\Entity]
#[ORM\Table(name: 'app_group')]
class Group extends AbstractGroup
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    protected AbstractUid $id;
}
