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

namespace Curler7\UserBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
abstract class AbstractVoter extends Voter
{
    public const POST = 'POST';
    public const GET = 'GET';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';
    public const REGISTER = 'REGISTER';
    public const LOGIN_LINK = 'LOGIN_LINK';
    protected const RESOURCE = '';

    public function __construct(protected Security $security) {}

    protected function supports(string $attribute, $subject): bool
    {
        if (!static::RESOURCE) {
            throw new \InvalidArgumentException('const RESOURCE must defined: '.static::RESOURCE);
        }

        return
            in_array(
                $attribute,
                [static::POST, static::GET, static::PUT, static::DELETE, static::REGISTER, static::LOGIN_LINK]
            ) &&
            is_subclass_of($subject, static::RESOURCE);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser() ?: null;

        return match ($attribute) {
            static::POST => $this->checkPost($user, $subject),
            static::GET => $this->checkGet($user, $subject),
            static::PUT => $this->checkPut($user, $subject),
            static::DELETE => $this->checkDelete($user, $subject),
            static::REGISTER => $this->checkRegister($user, $subject),
            static::LOGIN_LINK => $this->checkLoginLink($user, $subject),
            default => false,
        };
    }

    abstract protected function checkPost(?UserInterface $user, UserInterface $subject): bool;

    abstract protected function checkGet(?UserInterface $user, UserInterface $subject): bool;

    abstract protected function checkPut(?UserInterface $user, UserInterface $subject): bool;

    abstract protected function checkDelete(?UserInterface $user, UserInterface $subject): bool;

    abstract protected function checkRegister(?UserInterface $user, UserInterface $subject): bool;

    abstract protected function checkLoginLink(?UserInterface $user, UserInterface $subject): bool;
}