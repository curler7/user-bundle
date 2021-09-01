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

namespace App\DataFixtures;

use App\Entity\User;
use Curler7\UserBundle\Manager\UserManagerInterface;
use Curler7\UserBundle\Model\GroupInterface;
use Curler7\UserBundle\Model\UserInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(protected UserManagerInterface $userManager)
    {}

    const DATA = [
        [
            'username' => 'user',
            'email' => 'gunnar.s.gs@gmail.com',
            'password' => 'pass',
            'groups' => [0],
            'roles' => [],
            'enabled' => true,
            'full_name' => 'User',
        ],[
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'pass',
            'groups' => [0],
            'roles' => [UserInterface::ROLE_SUPER_ADMIN],
            'enabled' => true,
            'full_name' => 'Admin',
        ], [
            'username' => 'Neo',
            'email' => 'neo@example.com',
            'password' => 'matrix',
            'groups' => [0],
            'roles' => [],
            'enabled' => false,
            'full_name' => 'Neo',
        ], [
            'username' => 'Morpheus',
            'email' => 'morpheus@example.com',
            'password' => 'search',
            'groups' => [0],
            'roles' => [],
            'enabled' => true,
            'full_name' => 'Morpheus',
        ], [
            'username' => 'Trinity',
            'email' => 'trinity@example.com',
            'password' => 'whiterabbit',
            'groups' => [0],
            'roles' => [],
            'enabled' => true,
            'full_name' => 'Morpheus',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $last = array_key_last(static::DATA);

        foreach (static::DATA as $key => $data) {
            /** @var User $user */
            $user = $this->userManager->createUser(
                username: $data['username'],
                email: $data['email'],
                plainPassword: $data['password'],
                roles: $data['roles'],
                enabled: $data['enabled'],
            );

            $user->setFullName($data['full_name']);

            foreach ($data['groups'] as $id) {
                /** @var GroupInterface $group */
                $group = $this->getReference(GroupFixtures::REFERENCE.$id);
                $user->addGroup($group);
            }

            $this->userManager->updateUser($user, $last === $key);
        }
    }

    public function getDependencies(): array
    {
        return [
            GroupFixtures::class,
        ];
    }
}