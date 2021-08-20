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

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class DefaultController
{
    #[Route(path: '/', name: 'homepage')]
    public function index()
    {
        return new Response('<html><body>Hello World</body></html>', 200);
    }
}
