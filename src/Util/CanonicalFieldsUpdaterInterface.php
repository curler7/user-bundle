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

namespace Curler7\UserBundle\Util;

use Curler7\UserBundle\Model\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
interface CanonicalFieldsUpdaterInterface
{
    public function updateCanonicalFields(UserInterface $user): static;

    public function canonicalizeUsername(string $username): string;

    public function canonicalizeEmail(string $email): string;
}
