<?php

/*
 * This file is part of the Curler7UserBundle project.
 *
 * (c) Gunnar Suwe <suwe@smart-media.design>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Curler7\UserBundle\Tests\DependencyInjection;

use App\Entity\Group;
use App\Entity\User;
use Curler7\UserBundle\DependencyInjection\Curler7UserExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class Curler7UserExtensionTest extends AbstractExtensionTestCase
{
    private array $default = [
        'user_class' => User::class,
    ];

    protected function getContainerExtensions(): array
    {
        return [
            new Curler7UserExtension(),
        ];
    }

    public function testLoadedService()
    {
        $this->load($this->default);

        $this->assertContainerBuilderHasService('curler7_user.util.email_canonicalizer');
        $this->assertContainerBuilderHasService('curler7_user.util.username_canonicalizer');
        $this->assertContainerBuilderHasService('curler7_user.util.password_updater');
        $this->assertContainerBuilderHasService('curler7_user.user_manager');

        $this->assertContainerBuilderHasParameter('curler7_user.api_platform');

        $container = $this->container;
        $this->assertFalse($container->getParameter('curler7_user.api_platform'));
    }

    public function testApiPlatformLoading()
    {
        $config = array_merge($this->default, ['api_platform' => true]);
        $this->load($config);

        $this->assertContainerBuilderHasService('curler7_user.user_denormalizer');
    }

    public function testGroupLoading()
    {
        $config = array_merge($this->default, [
            'group' => [
                'group_class' => Group::class,
            ],
        ]);

        $this->load($config);

        $this->assertContainerBuilderHasService('curler7_user.group_manager');
        $this->assertContainerBuilderHasParameter('curler7_user.model.group.class');
    }
}
