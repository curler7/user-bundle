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

use Curler7\UserBundle\Manager\GroupManagerInterface;
use Curler7\UserBundle\Model\UserInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class GroupFixtures extends Fixture
{
    public const REFERENCE = 'group_reference_';

    public function __construct(protected GroupManagerInterface $groupManager)
    {}

    const DATA = [
        [
            'name' => 'Zion',
            'roles' => [UserInterface::ROLE_DEFAULT]
        ], [
            'name' => 'Nebuchadnezzar',
            'roles' => [UserInterface::ROLE_DEFAULT, UserInterface::ROLE_SUPER_ADMIN]
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $last = array_key_last(static::DATA);

        foreach (static::DATA as $key => $data) {
            ($group = $this->groupManager->createGroup($data['name']))->setRoles($data['roles']);

            $this->addReference(self::REFERENCE.$key, $group);

            $this->groupManager->updateGroup($group, $last === $key);
        }
    }
}