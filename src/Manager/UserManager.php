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

namespace Curler7\UserBundle\Manager;

use Curler7\UserBundle\Manager\AbstractUserManager;
use Curler7\UserBundle\Model\UserInterface;
use Curler7\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Curler7\UserBundle\Util\PasswordUpdaterInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use JetBrains\PhpStorm\Pure;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserManager extends AbstractUserManager
{
    #[Pure]
    public function __construct(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        private ObjectManager $objectManager,
        private string $class
    ) {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater);
    }

    public function deleteUser(UserInterface $user): static
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();

        return $this;
    }

    public function getClass(): string
    {
        if (str_contains($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }

        return $this->class;
    }

    public function findUserBy(array $criteria): ?UserInterface
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    public function findUsers(): array
    {
        return $this->getRepository()->findAll();
    }

    public function reloadUser(UserInterface $user): static
    {
        $this->objectManager->refresh($user);

        return $this;
    }

    public function updateUser(UserInterface $user, $flush = true): static
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        $this->objectManager->persist($user);

        if ($flush) {
            $this->objectManager->flush();
        }

        return $this;
    }

    protected function getRepository(): ObjectRepository
    {
        return $this->objectManager->getRepository($this->getClass());
    }
}
