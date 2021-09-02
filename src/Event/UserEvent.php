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

namespace Curler7\UserBundle\Event;

use Curler7\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserEvent extends Event implements UserEventInterface
{
    public function __construct(
        protected UserInterface $user,
        protected ?array $context = null,
        protected ?Request $request = null,
        protected ?Response $response = null,
    ) {}

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }
}