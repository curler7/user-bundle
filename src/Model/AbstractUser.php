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

use Curler7\UserBundle\Model\Aware\Traits\DateControlAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\EmailAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\EmailCanonicalAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\EnabledAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\LastLoginAtAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\LoginLinkRequestedAtAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\PasswordAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\PlainPasswordAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\ResourceAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\RolesAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\ShareAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\UserControlAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\UsernameAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\UsernameCanonicalAwareTrait;
use Curler7\UserBundle\Model\Aware\Traits\VerifiedAwareTrait;
use Symfony\Component\Uid\AbstractUid;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
abstract class AbstractUser implements UserInterface
{
    use ResourceAwareTrait,
        DateControlAwareTrait,
        UserControlAwareTrait,
        RolesAwareTrait,
        VerifiedAwareTrait,
        ShareAwareTrait,
        EnabledAwareTrait,
        UsernameAwareTrait,
        UsernameCanonicalAwareTrait,
        EmailAwareTrait,
        EmailCanonicalAwareTrait,
        PlainPasswordAwareTrait,
        PasswordAwareTrait,
        LastLoginAtAwareTrait,
        LoginLinkRequestedAtAwareTrait {
        ResourceAwareTrait::__construct as protected __constructResource;
        DateControlAwareTrait::__construct as protected __constructDateControl;
        UserControlAwareTrait::__construct as protected __constructUserControl;
    }

    public ?string $__toString = null;

    public function __construct(AbstractUid $id = null, UserInterface $createdFrom = null)
    {
        $this->__constructResource($id);
        $this->__constructDateControl();
        $this->__constructUserControl($createdFrom);
    }

    public function __toString(): string
    {
        return $this->username ?? 'Unknown username';
    }

    public function loadUserByIdentifier(): string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return 'username';
    }

    public function getSalt() {}
}
