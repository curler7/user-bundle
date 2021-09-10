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

namespace Curler7\UserBundle\Controller;

use Curler7\UserBundle\Model\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class LoginLinkController extends AbstractController
{
    public function __construct(
        private string $resourceClass,
        private NotifierInterface $notifier,
        private LoginLinkHandlerInterface $loginLinkHandler,
        private TranslatorInterface $translator,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var UserInterface $user */
        if (!$user = $this->entityManager->getRepository($this->resourceClass)->loadUserByIdentifier($request->toArray()['identifier'] ?? null)) {
            return new JsonResponse(['response.entity_not_found'], 404);
        }

        $this->notifier->send(
            new LoginLinkNotification($this->loginLinkHandler->createLoginLink($user), $this->translator->trans('user.login_link.notification.subject')),
            new Recipient($user->getEmail())
        );

        return new JsonResponse();
    }
}