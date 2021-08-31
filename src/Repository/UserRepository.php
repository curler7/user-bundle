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

namespace Curler7\UserBundle\Repository;

use Curler7\UserBundle\Model\UserInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /** @throws NonUniqueResultException */
    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        $qb = $this->createQueryBuilder('o');

        foreach (UserInterface::IDENTIFIERS as $value) {
            $qb->orWhere($qb->expr()->eq('o.'.$value, ':identifier'));
        }

        return $qb
            ->andWhere($qb->expr()->eq('o.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->setParameter('identifier', $identifier)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countSuperAdminEnabled(): int
    {
        $qb = $this->createQueryBuilder('o');

        return $qb
            ->select('count(o.id)')
            ->leftJoin('o.groups', 'g')
            ->andWhere($qb->expr()->in(UserInterface::ROLE_SUPER_ADMIN, 'o.roles'))
            ->andWhere($qb->expr()->in(UserInterface::ROLE_SUPER_ADMIN, 'o.roles'))
            ->andWhere($qb->expr()->eq('o.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /** @throws \RuntimeException */
    public function loadUserByUsername(string $username)
    {
        throw new \RuntimeException('Depreciated "loadUserByIdentifier" should called');
    }
}