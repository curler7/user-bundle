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

use Curler7\UserBundle\Model\UserInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserRegistration implements UserRegistrationInterface
{
    public function __construct(
        protected NotifierInterface $notifier,
        protected LoginLinkHandlerInterface $loginLinkHandler,
        protected TranslatorInterface $translator,
    ) {}

    public function register(UserInterface $user): static
    {
        $this->notifier->send(
            new LoginLinkNotification(
                $this->loginLinkHandler->createLoginLink($user),
                $this->translator->trans('user.register.notification.subject')
            ),
            new Recipient($user->getEmail())
        );

        return $this;
    }
}