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

use App\Entity\User;
use Curler7\UserBundle\Model\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class LastSuperAdminUserValidator extends ConstraintValidator
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected RequestStack $requestStack,
    ) {}

    /** @param UserInterface $value */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof LastSuperAdminUser) {
            throw new UnexpectedTypeException($constraint, LastSuperAdminUser::class);
        }

        if (!$value instanceof UserInterface) {
            throw new \InvalidArgumentException('Value must instance of '.UserInterface::class);
        }

        if (
            ('DELETE' === $this->requestStack->getCurrentRequest()->getMethod() || !$value->isEnabled()) &&
            0 === $this->entityManager->getRepository(User::class)->countSuperAdminEnabled($value->getId())
        ) {
            $this->context->buildViolation($constraint->message)
                ->atPath('enabled')
                ->addViolation();
        }
    }
}