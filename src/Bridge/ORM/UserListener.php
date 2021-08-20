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

use Curler7\UserBundle\Model\UserInterface;
use Curler7\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Curler7\UserBundle\Util\PasswordUpdaterInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserListener implements EventSubscriber
{
    public function __construct(
        private PasswordUpdaterInterface $passwordUpdater,
        private CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater
    ) {}

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var UserInterface $user */
        if (($user = $args->getObject()) instanceof UserInterface) {
            $this->updateUserFields($user);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        /** @var UserInterface $user */
        if (($user = $args->getObject()) instanceof UserInterface) {
            $this->updateUserFields($user);
            $this->recomputeChangeSet($args->getObjectManager(), $user);
        }
    }

    private function updateUserFields(UserInterface $user)
    {
        $this->canonicalFieldsUpdater->updateCanonicalFields($user);
        $this->passwordUpdater->hashPassword($user);
    }

    private function recomputeChangeSet(ObjectManager $om, UserInterface $user)
    {
        $om->getUnitOfWork()->recomputeSingleEntityChangeSet($om->getClassMetadata(\get_class($user)), $user);
    }
}
