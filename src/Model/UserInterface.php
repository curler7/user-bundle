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

namespace Curler7\UserBundle\Model;

use Curler7\UserBundle\Model\Aware\Interfaces\DateControlAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\EmailAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\EmailCanonicalAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\EnabledAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\LastLoginAtAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\LoginLinkRequestedAtAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\PasswordAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\PlainPasswordAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\ResourceAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\RolesAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\ShareAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\UserControlAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\UsernameAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\UsernameCanonicalAwareInterface;
use Curler7\UserBundle\Model\Aware\Interfaces\VerifiedAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface UserInterface extends
    BaseUserInterface,
    \Stringable,
    ResourceAwareInterface,
    DateControlAwareInterface,
    UserControlAwareInterface,
    RolesAwareInterface,
    VerifiedAwareInterface,
    ShareAwareInterface,
    EnabledAwareInterface,
    UsernameAwareInterface,
    UsernameCanonicalAwareInterface,
    EmailAwareInterface,
    EmailCanonicalAwareInterface,
    PlainPasswordAwareInterface,
    PasswordAwareInterface,
    LastLoginAtAwareInterface,
    LoginLinkRequestedAtAwareInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    const IS_AUTHENTICATED_REMEMBERED = 'IS_AUTHENTICATED_REMEMBERED';
    const IS_AUTHENTICATED_FULLY = 'IS_AUTHENTICATED_FULLY';
    const IS_AUTHENTICATED_ANONYMOUSLY = 'IS_AUTHENTICATED_ANONYMOUSLY';
    const IS_ANONYMOUS = 'IS_ANONYMOUS';
    const IS_REMEMBERED = 'IS_REMEMBERED';
    const IS_IMPERSONATOR = 'IS_IMPERSONATOR';

    const IDENTIFIERS = ['username', 'email'];
}
