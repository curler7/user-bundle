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
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler as LexikAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        protected LexikAuthenticationSuccessHandler $lexikAuthenticationSuccessHandler,
        protected EntityManagerInterface $entityManager,
    ) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JWTAuthenticationSuccessResponse
    {
        /** @var UserInterface $user */
        $user = $token->getUser();
        $user->setVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        return $this->lexikAuthenticationSuccessHandler->handleAuthenticationSuccess($user);
    }
}