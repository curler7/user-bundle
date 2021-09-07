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
        'model' => [
            'user_class' => User::class,
            'group_class' => Group::class,
        ]
    ];

    protected function getContainerExtensions(): array
    {
        return [
            new Curler7UserExtension(),
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->container->setParameter('kernel.cache_dir', \sys_get_temp_dir());
    }

    public function testLoadedService()
    {
        $this->load($this->default);

        $this->assertContainerBuilderHasService('curler7_user.api_platform.auto_group_resource_metadata_factory');
        $this->assertContainerBuilderHasService('curler7_user.command.create_user');
        $this->assertContainerBuilderHasService('curler7_user.controller.login_link');
        $this->assertContainerBuilderHasService('curler7_user.model.group_manager');
        $this->assertContainerBuilderHasService('curler7_user.model.user_manager');
        $this->assertContainerBuilderHasService('curler7_user.model.object_manager');
        $this->assertContainerBuilderHasService('curler7_user.open_api.jwt_decorator');
        $this->assertContainerBuilderHasService('curler7_user.security.groups_context_builder');
        $this->assertContainerBuilderHasService('curler7_user.security.authentication_success_handler');
        $this->assertContainerBuilderHasService('curler7_user.security.user_voter');
        $this->assertContainerBuilderHasService('curler7_user.security.group_voter');
        $this->assertContainerBuilderHasService('curler7_user.serializer.user_normalizer');
        $this->assertContainerBuilderHasService('curler7_user.subscriber.jwt_subscriber');
        $this->assertContainerBuilderHasService('curler7_user.subscriber.validate_before_delete');
        $this->assertContainerBuilderHasService('curler7_user.util.canonical_fields_updater');
        $this->assertContainerBuilderHasService('curler7_user.util.canonicalizer');
        $this->assertContainerBuilderHasService('curler7_user.util.password_updater');
        $this->assertContainerBuilderHasService('curler7_user.util.user_registration');
        $this->assertContainerBuilderHasService('curler7_user.util.user_spy');
        $this->assertContainerBuilderHasService('curler7_user.util.email_canonicalizer');
        $this->assertContainerBuilderHasService('curler7_user.util.username_canonicalizer');
        $this->assertContainerBuilderHasService('curler7_user.validator.last_super_admin_user');

        $container = $this->container;
        $this->assertIsString($container->getParameter('curler7_user.model.user.class'));
        $this->assertIsString($container->getParameter('curler7_user.model.group.class'));
        $this->assertIsString($container->getParameter('curler7_user.model.model_manager_name'));
        $this->assertIsString($container->getParameter('curler7_user.model.storage'));
    }
}
