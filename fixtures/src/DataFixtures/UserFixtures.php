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
use Doctrine\Persistence\ObjectManager;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserFixtures
{
    public function __construct(protected UserManagerInterface $userManager)
    {}

    const DATA = [
        [
            'username' => 'Neo',
            'email' => 'neo@example.com',
            'password' => 'matrix',
        ], [
            'username' => 'Morpheus',
            'email' => 'morpheus@example.com',
            'password' => 'search',
        ], [
            'username' => 'Trinity',
            'email' => 'trinity@example.com',
            'password' => 'whiterabbit',
        ],
    ];

    /** @throws \Exception */
    public function load(ObjectManager $manager): void
    {
        foreach (static::DATA as $data) {
            ($user = $this->userManager->createUser())
                ->setUsername($data['username'])
                ->setEmail($data['email'])
                ->setPlainPassword($data['password'])
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}