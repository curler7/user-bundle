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
use Curler7\UserBundle\Util\LoginLinkSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class LoginLinkController extends AbstractController
{
    public function __construct(
        private string $resourceClass,
        private EntityManagerInterface $entityManager,
        private LoginLinkSender $loginLinkSender,
        private Security $security,
        private bool $loginLinkShare,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var UserInterface $user */
        if (!$user = $this->entityManager->getRepository($this->resourceClass)->loadUserByIdentifier(
            $request->toArray()['identifier'] ?? null
        )) {
            return new JsonResponse(status: 404);
        }

        $this->loginLinkSender->send(
            $user,
            $this->loginLinkShare &&
            $this->security->isGranted(UserInterface::ROLE_DEFAULT) &&
            ($request->toArray()['share_to'] ?? null) ?
                $request->toArray()['share_to'] :
                $user->getEmail()
        );

        return new JsonResponse();
    }
}