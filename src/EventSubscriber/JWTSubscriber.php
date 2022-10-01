<?php

/*
 * This file is part of the Curler7UserBundle project.
 *
 * (c) Gunnar Suwe <suwe@smart-media.design>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Curler7\UserBundle\EventSubscriber;

use Curler7\UserBundle\Manager\UserManagerInterface;
use Curler7\UserBundle\Model\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTSubscriber implements EventSubscriberInterface
{
    public function __construct(protected UserManagerInterface $userManager) {}

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
        } else {
            $this->userManager->updateUser($user->setLastLoginAt(new \DateTime()));
        }
        
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
