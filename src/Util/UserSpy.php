<?php

/*
 * This file is part of the Curler7ApiTestBundle project.
 *
 * (c) Gunnar Suwe <suwe@smart-media.design>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Curler7\UserBundle\Util;

use Curler7\UserBundle\Event\UserEvent;
use Curler7\UserBundle\Event\UserEventInterface;
use Curler7\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserSpy implements UserSpyInterface
{
    public function __construct(protected EventDispatcherInterface $eventDispatcher) {}

    public function spy(UserInterface $user, array $data, array $context): static
    {
        if (isset($data['password'])) {
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                UserEventInterface::USER_PASSWORD_CHANGED,
            );
        }
        if (($data['email'] ?? null) !== ($context['previous_data'] ?? null)) {
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                UserEventInterface::USER_EMAIL_CHANGED,
            );
        }
        if (($data['username'] ?? null) !== ($context['previous_data'] ?? null)) {
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                UserEventInterface::USER_USERNAME_CHANGED,
            );
        }
        if (($data['roles'] ?? null) !== ($context['previous_data'] ?? null)) {
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                UserEventInterface::USER_ROLES_CHANGED
            );
        }
        if (($data['groups'] ?? null) !== ($context['previous_data'] ?? null)) {
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                UserEventInterface::USER_GROUPS_CHANGED
            );
        }
        if (($data['enabled'] ?? null) !== ($context['previous_data'] ?? null)) {
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                $data['enabled'] ? UserEventInterface::USER_ENABLED : UserEventInterface::USER_DISABLED,
            );
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                UserEventInterface::USER_ENABLED_CHANGED,
            );
        }
        if ('register' === ($context['collection_operation_name'] ?? null)) {
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                UserEventInterface::USER_REGISTRATION_INITIALIZE,
            );
        }
        if ('post' === ($context['collection_operation_name'] ?? null)) {
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                UserEventInterface::USER_CREATED,
            );
        }
        if ('put' === ($context['item_operation_name'] ?? null) ||
            'patch' === ($context['item_operation_name'] ?? null)
        ) {
            $this->eventDispatcher->dispatch(
                new UserEvent($user, $context),
                UserEventInterface::USER_UPDATED,
            );
        }

        return $this;
    }
}