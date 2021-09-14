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
use Curler7\UserBundle\Model\UserInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserFixtures extends Fixture
{
    public function __construct(protected UserManagerInterface $userManager)
    {}

    const DATA = [
        [
            'username' => 'user',
            'email' => 'gunnar.s.gs@gmail.com',
            'password' => 'pass',
            'roles' => [],
            'enabled' => true,
            'verified' => true,
            'fullName' => 'User',
        ],[
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'pass',
            'roles' => [UserInterface::ROLE_SUPER_ADMIN],
            'enabled' => true,
            'verified' => true,
            'fullName' => 'Admin',
        ], [
            'username' => 'Neo',
            'email' => 'neo@example.com',
            'password' => 'matrix',
            'roles' => [],
            'enabled' => false,
            'verified' => false,
            'fullName' => 'Neo',
        ], [
            'username' => 'Morpheus',
            'email' => 'morpheus@example.com',
            'password' => 'search',
            'roles' => [UserInterface::ROLE_SUPER_ADMIN],
            'enabled' => false,
            'verified' => false,
            'fullName' => 'Morpheus',
        ], [
            'username' => 'Trinity',
            'email' => 'trinity@example.com',
            'password' => 'whiterabbit',
            'roles' => [],
            'enabled' => false,
            'verified' => false,
            'fullName' => 'Morpheus',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $last = array_key_last(static::DATA);

        foreach (static::DATA as $key => $data) {
            /** @var User $user */
            $user = $this->userManager->createUser()
                ->setUsername($data['username'])
                ->setEmail($data['email'])
                ->setPlainPassword($data['password'])
                ->setRoles($data['roles'])
                ->setEnabled($data['enabled'])
                ->setVerified($data['verified'])
                ->setFullName($data['fullName']);

            $this->userManager->updateUser($user, $last === $key);
        }
    }
}