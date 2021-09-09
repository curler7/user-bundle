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

namespace Curler7\UserBundle\Security\Authentication;

use Curler7\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        /** @var UserInterface $user */
        $user = $token->getUser();

        $user->setEnabled(true);
    }
}