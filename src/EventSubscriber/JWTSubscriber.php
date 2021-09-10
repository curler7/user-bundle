<?php

namespace Curler7\UserBundle\EventSubscriber;

use Curler7\UserBundle\Manager\UserManagerInterface;
use Curler7\UserBundle\Model\UserInterface;
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
        /** @var UserInterface $user */
        $user = $event->getUser();
        
        if (!$user->isVerified()) {
            $event->setData([]);
            $event->getResponse()->setStatusCode(401, 'User not verified');

            return;
        }

        $this->userManager->updateUser($user->setLastLogin(new \DateTime()));

        $this->addRolesToPayload($event);
    }

    protected function addRolesToPayload($event): void
    {
        /** @var UserInterface $user */
        if (!$user = $event->getUser()) {
            return;
        }

        $payload = $event->getData();
        $payload['id'] = $user->getId();

        $event->setData($payload);
    }
}
