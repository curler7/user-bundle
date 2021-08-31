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

namespace Curler7\UserBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 * @Annotation
 */
#[\Attribute]
class LastSuperAdminUser extends Constraint
{
    public string $message = 'curler7_user.user.enabled.last_super_admin';
}