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

namespace Curler7\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class Curler7UserExtension extends Extension implements PrependExtensionInterface
{
    private const DOCTRINE_DRIVERS = [
        'orm' => [
            'registry' => 'doctrine',
            'tag'      => 'doctrine.event_subscriber',
        ],
        'mongodb' => [
            'registry' => 'doctrine_mongodb',
            'tag'      => 'doctrine_mongodb.odm.event_subscriber',
        ],
        'couchdb' => [
            'registry'       => 'doctrine_couchdb',
            'tag'            => 'doctrine_couchdb.event_subscriber',
            'listener_class' => 'Curler7\UserBundle\Bridge\CouchDB\UserListener',
        ],
        'phpcr' => [
            'registry'       => 'doctrine_phpcr',
            'tag'            => 'doctrine_phpcr.event_subscriber',
            'listener_class' => 'Curler7\UserBundle\Bridge\Phpcr\UserListener',
        ],
    ];

    public function prepend(ContainerBuilder $container): void
    {
        $config = (new Processor())->processConfiguration(
            new Configuration(),
            $container->getParameterBag()->resolveValue($container->getExtensionConfig($this->getAlias()))
        );

        $paths = [];
        !$config['config']['resource_user'] ?: $paths[] = __DIR__ . '/../../config/resource/user';
        !$config['config']['resource_group'] ?: $paths[] = __DIR__ . '/../../config/resource/group';
        $container->prependExtensionConfig('api_platform', ['mapping' => ['paths' => $paths]]);

        $paths = [];
        !$config['config']['serializer_user'] ?: $paths[] = __DIR__ . '/../../config/serializer/user';
        !$config['config']['serializer_group'] ?: $paths[] = __DIR__ . '/../../config/serializer/group';
        $container->prependExtensionConfig('framework', ['serializer' => ['mapping' => ['paths' => $paths]]]);

        $paths = [];
        !$config['config']['validation_user'] ?: $paths[] = __DIR__ . '/../../config/validation/user';
        !$config['config']['validation_group'] ?: $paths[] = __DIR__ . '/../../config/validation/group';
        !$config['config']['storage_validation_user'] ?: $paths[] = __DIR__ . '/../../config/storage_validation/user';
        !$config['config']['storage_validation_group'] ?: $paths[] = __DIR__ . '/../../config/storage_validation/group';
        $container->prependExtensionConfig('framework', ['validation' => ['mapping' => ['paths' => $paths]]]);
    }

    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = (new Processor())->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        // Model
        $container->setParameter('curler7_user.model.user.class', $config['user_class']);
        $container->setParameter('curler7_user.model.group.class', $config['group_class']);
        $container->setParameter('curler7_user.model_manager_name', $config['model_manager_name']);
        $container->setParameter('curler7_user.model.storage', $config['db_driver']);
        $container->setParameter('curler7_user.model.group_manager.class', $config['service']['group_manager']);
        $container->setParameter('curler7_user.model.user_manager.class', $config['service']['user_manager']);
        $container->setParameter('curler7_user.model.object_manager.class', $config['service']['object_manager']);
        // Serializer
        $container->setParameter('curler7_user.serializer.user_normalizer.class', $config['service']['user_normalizer']);
        // Api platform
        $container->setParameter('curler7_user.api_platform.auto_group_resource_metadata_factory.class', $config['service']['auto_group_resource_metadata_factory']);
        // Security
        $container->setParameter('curler7_user.security.groups_context_builder.class', $config['service']['groups_context_builder']);
        $container->setParameter('curler7_user.security.authentication_success_handler.class', $config['service']['security_authentication_success_handler']);
        $container->setParameter('curler7_user.security.user_voter.class', $config['service']['user_voter']);
        $container->setParameter('curler7_user.security.group_voter.class', $config['service']['group_voter']);
        // Open api
        $container->setParameter('curler7_user.open_api.jwt_decorator.class', $config['service']['jwt_decorator']);
        $container->setParameter('curler7_user.open_api.jwt_decorator.user', $config['jwt_decorator']['user']);
        $container->setParameter('curler7_user.open_api.jwt_decorator.password', $config['jwt_decorator']['password']);
        $container->setParameter('curler7_user.open_api.jwt_decorator.path', $config['jwt_decorator']['path']);
        // Subscriber
        $container->setParameter('curler7_user.subscriber.jwt_subscriber.class', $config['service']['subscriber_jwt_subscriber']);
        $container->setParameter('curler7_user.subscriber.validate_before_delete.class', $config['service']['validate_before_delete_subscriber']);
        // Controller
        $container->setParameter('curler7_user.controller.login_link.class', $config['service']['login_link_controller']);
        // Command
        $container->setParameter('curler7_user.command.create_user.class', $config['service']['command_create_user']);
        // Utils
        $container->setParameter('curler7_user.util.canonical_fields_updater.class', $config['service']['canonical_fields_updater']);
        $container->setParameter('curler7_user.util.canonicalizer.class', $config['service']['canonicalizer']);
        $container->setParameter('curler7_user.util.password_updater.class', $config['service']['password_updater']);
        $container->setParameter('curler7_user.util.user_registration.class', $config['service']['user_registration']);
        $container->setParameter('curler7_user.util.user_spy.class', $config['service']['user_spy']);
        $container->setAlias('curler7_user.util.util.email_canonicalizer', $config['service']['email_canonicalizer']);
        $container->setAlias('curler7_user.util.util.username_canonicalizer', $config['service']['username_canonicalizer']);
        // Validator
        $container->setParameter('curler7_user.validator.last_super_admin_user.class', $config['service']['validator_last_super_admin_user']);

        foreach ([
            'api_platform', 'command', 'controller',
            'event_subscriber', 'model', 'open_api',
            'security', 'serializer', 'util', 'validator',
         ] as $file) {
            $loader->load($file.'.xml');
        }

        $this->loadDbDriver($loader, $container, $config);
    }

    /**
     * @throws \Exception
     */
    private function loadDbDriver(XmlFileLoader $loader, ContainerBuilder $container, $config)
    {
        if ('custom' !== $config['db_driver']) {
            if (isset(self::DOCTRINE_DRIVERS[$config['db_driver']])) {
                $loader->load('doctrine.xml');
                $container->setAlias('curler7_user.doctrine_registry', new Alias(self::DOCTRINE_DRIVERS[$config['db_driver']]['registry'], false));
            } else {
                $loader->load(sprintf('%s.xml', $config['db_driver']));
            }
            $container->setParameter($this->getAlias().'.backend_type_'.$config['db_driver'], true);
        }

        if (isset(self::DOCTRINE_DRIVERS[$config['db_driver']])) {
            $definition = $container->getDefinition('curler7_user.object_manager');
            $definition->setFactory([new Reference('curler7_user.doctrine_registry'), 'getManager']);
        }
    }
}
