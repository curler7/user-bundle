<?php

namespace Curler7\UserBundle\EventSubscriber;

use Curler7\UserBundle\Manager\UserManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class JWTSubscriber implements EventSubscriberInterface
{
    public function __construct(protected UserManagerInterface $userManager) {}

    #[ArrayShape([
        'lexik_jwt_authenticationlexik_jwt_authentication.on_jwt_created' => "string",
        'lexik_jwt_authentication.on_authentication_success' => "string"
    ])]
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authenticationlexik_jwt_authentication.on_jwt_created' => 'onJWTCreated',
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccessResponse'
        ];
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $this->addRolesToPayload($event);
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $this->userManager->updateUser($event->getUser()->setLastLogin(new \DateTime()));

        $this->addRolesToPayload($event);
    }

    protected function addRolesToPayload($event): void
    {
        if (!$user = $event->getUser()) {
            return;
        }

        $payload = $event->getData();
        $payload['roles'] = $user->getRoles();
        $payload['id'] = $user->getId();

        $event->setData($payload);
    }
}
