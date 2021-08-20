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

namespace Curler7\UserBundle\Bridge\ORM;

use Curler7\UserBundle\Manager\AbstractGroupManager;
use Curler7\UserBundle\Model\GroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class GroupManager extends AbstractGroupManager
{
    public function __construct(private ObjectManager $objectManager, private string $class)
    {}

    public function deleteGroup(GroupInterface $group): static
    {
        $this->objectManager->remove($group);
        $this->objectManager->flush();

        return $this;
    }

    public function findGroupBy(array $criteria): ?GroupInterface
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    public function findGroups(): array
    {
        return $this->getRepository()->findAll();
    }

    public function getClass(): string
    {
        if (str_contains($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }

        return $this->class;
    }

    public function updateGroup(GroupInterface $group, $flush = true): static
    {
        $this->objectManager->persist($group);

        if ($flush) {
            $this->objectManager->flush();
        }

        return $this;
    }

    public function getRepository(): ObjectRepository
    {
        return $this->objectManager->getRepository($this->getClass());
    }
}
