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

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class Canonicalizer implements CanonicalizerInterface
{
    public function canonicalize(string $string): string
    {
        return mb_convert_case($string, MB_CASE_LOWER, mb_detect_encoding($string, mb_detect_order(), true) ?: null);
    }
}
