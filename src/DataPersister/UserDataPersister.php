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

namespace Curler7\UserBundle\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Curler7\UserBundle\Model\UserInterface;
use Curler7\UserBundle\Util\LoginLinkSenderInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private LoginLinkSenderInterface           $loginLinkSender,
        private bool                               $loginLinkRegister,
        private bool                               $loginLinkPost,
    ) {}

    public function supports($data, array $context = []): bool
    {
        return $this->decorated->supports($data, $context);
    }

    public function persist($data, array $context = [])
    {
        $result = $this->decorated->persist($data, $context);

        if ($data instanceof UserInterface && (
            (
                $this->loginLinkPost && (
                    'post' === ($context['collection_operation_name'] ?? null) ||
                    'create' === ($context['graphql_operation_name'] ?? null)
                )
            ) || (
                $this->loginLinkRegister &&
                'register' === ($context['collection_operation_name'] ?? null)
            ))
        ) {
            $this->loginLinkSender->send($data, subject: 'user.register.notification.subject');
        }

        return $result;
    }

    public function remove($data, array $context = [])
    {
        return $this->decorated->remove($data, $context);
    }
}