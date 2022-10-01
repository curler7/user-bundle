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

namespace Curler7\UserBundle\Event;

use Curler7\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface UserEventInterface
{
    public const USER_ENABLED = 'curler7_user.user.enabled';

    public const USER_DISABLED = 'curler7_user.user.disabled';

    public const USER_ENABLED_CHANGED = 'fos_user.user.password_changed';

    public const USER_PASSWORD_CHANGED = 'fos_user.user.password_changed';

    public const USER_EMAIL_CHANGED = 'fos_user.user.password_changed';

    public const USER_USERNAME_CHANGED = 'fos_user.user.password_changed';

    public const USER_ROLES_CHANGED = 'fos_user.user.password_changed';

    public const USER_GROUPS_CHANGED = 'fos_user.user.password_changed';

    public const USER_REGISTRATION_INITIALIZE = 'fos_user.user.created';

    public const USER_CREATED = 'fos_user.user.created';

    public const USER_UPDATED = 'fos_user.user.created';

    public function getUser(): UserInterface;

    public function getRequest(): ?Request;

    public function getResponse(): ?Response;

    public function getContext(): ?array;
}