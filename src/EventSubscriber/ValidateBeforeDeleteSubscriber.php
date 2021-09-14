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

namespace Curler7\UserBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\Validator\ValidatorInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
final class ValidateBeforeDeleteSubscriber implements EventSubscriberInterface
{
    public const DEFAULT_VALIDATION_GROUPS = ['delete'];

    public function __construct(
        private ValidatorInterface $validator,
        private ?array $validationGroups = null
    ) {}

    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['validate', EventPriorities::PRE_WRITE],
        ];
    }

    public function validate(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();

        if ($entity instanceof Response || $event->getRequest()->getMethod() !== 'DELETE') {
            return;
        }

        $this->validator->validate($entity, ['groups' => $this->validationGroups ?? self::DEFAULT_VALIDATION_GROUPS]);
    }
}