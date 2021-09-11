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

use Curler7\UserBundle\Manager\UserManagerInterface;
use Curler7\UserBundle\Model\UserInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class LoginLinkSender implements LoginLinkSenderInterface
{
    public function __construct(
        protected NotifierInterface $notifier,
        protected LoginLinkHandlerInterface $loginLinkHandler,
        protected TranslatorInterface $translator,
        protected UserManagerInterface $userManager,
    ) {}

    public function send(
        UserInterface $user,
        ?string $email = null,
        string $subject = 'user.login_link.notification.subject',
    ): static
    {
        $this->notifier->send(
            new LoginLinkNotification(
                $this->loginLinkHandler->createLoginLink($user),
                $this->translator->trans($subject)
            ),
            new Recipient($email ?? $user->getEmail())
        );

        $this->userManager->updateUser($user->setLoginLinkRequestedAt(new \DateTime()));

        return $this;
    }
}