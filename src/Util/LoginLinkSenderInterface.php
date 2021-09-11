<?php

declare(strict_types=1);

namespace Curler7\UserBundle\Util;

use Curler7\UserBundle\Model\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface LoginLinkSenderInterface
{
    public function send(
        UserInterface $user,
        ?string $email = null,
        string $subject = 'user.login_link.notification.subject',
    ): static;
}