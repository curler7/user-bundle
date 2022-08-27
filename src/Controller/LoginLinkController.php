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

use ApiPlatform\Core\JsonLd\Serializer\ItemNormalizer;
use ApiPlatform\Core\JsonLd\Serializer\ObjectNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Curler7\UserBundle\Model\UserInterface;
use Curler7\UserBundle\Util\LoginLinkSenderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class LoginLinkController extends AbstractController
{
    const VALIDATION_GROUPS = ['login_link'];

    public function __construct(
        private string                      $resourceClass,
        private EntityManagerInterface      $entityManager,
        private LoginLinkSenderInterface    $loginLinkSender,
        private Security                    $security,
        private bool                        $loginLinkShare,
        protected ItemNormalizer            $itemNormalizer,
        private ValidatorInterface          $validator,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(Request $request): JsonResponse
    {
        $object = $this->itemNormalizer->denormalize(
            ['email' => $request->toArray()['identifier'] ?? ''],
            $this->resourceClass
        );
        throw new \Exception('E-Mail: ' . $request->toArray()['identifier']);
        $this->validator->validate($object, ['groups' => self::VALIDATION_GROUPS]);

        /** @var UserInterface $user */
        if (!$user = $this->entityManager->getRepository($this->resourceClass)->findOneBy(
            ['email' => $request->toArray()['identifier'] ?? null]
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