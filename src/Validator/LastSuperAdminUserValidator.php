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

namespace Curler7\UserBundle\Validator;

use Curler7\UserBundle\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class LastSuperAdminUserValidator extends ConstraintValidator
{
    public function __construct(protected ?UserRepository $userRepository = null) {}

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof LastSuperAdminUser) {
            throw new UnexpectedTypeException($constraint, LastSuperAdminUser::class);
        }

        if (!$value && 1 === $this->userRepository?->countSuperAdminEnabled()) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}