<?php

declare(strict_types=1);

namespace Curler7\UserBundle\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Curler7\UserBundle\Model\UserInterface;
use Curler7\UserBundle\Util\LoginLinkSenderInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
final class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface          $decorated,
        private LoginLinkSenderInterface    $loginLinkSender,
        private bool                        $loginLinkRegister,
        private bool                        $loginLinkPost,
    ) {}
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
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

        return $this->decorated->process($data, $operation, $uriVariables, $context);
    }
}