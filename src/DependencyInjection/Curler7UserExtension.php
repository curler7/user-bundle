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

        // Config
        $container->setParameter('curler7_user.config.login_link_post', $config['config']['login_link_post']);
        $container->setParameter('curler7_user.config.login_link_register', $config['config']['login_link_register']);
        $container->setParameter('curler7_user.config.login_link_share', $config['config']['login_link_share']);
        // Api platform
        $container->setParameter('curler7_user.api_platform.auto_group_resource_metadata_factory.class', $config['api_platform']['auto_group_resource_metadata_factory']);
        // Command
        $container->setParameter('curler7_user.command.create_user.class', $config['command']['create_user']);
        // Controller
        $container->setParameter('curler7_user.controller.login_link.class', $config['controller']['login_link']);
        // Model
        $container->setParameter('curler7_user.model.user.class', $config['model']['user_class']);
        $container->setParameter('curler7_user.model.group.class', $config['model']['group_class']);
        $container->setParameter('curler7_user.model.model_manager_name', $config['model']['model_manager_name']);
        $container->setParameter('curler7_user.model.storage', $config['model']['db_driver']);
        $container->setParameter('curler7_user.model.group_manager.class', $config['model']['group_manager']);
        $container->setParameter('curler7_user.model.user_manager.class', $config['model']['user_manager']);
        $container->setParameter('curler7_user.model.object_manager.class', $config['model']['object_manager']);
        // Open api
        $container->setParameter('curler7_user.open_api.jwt_decorator.class', $config['open_api']['jwt_decorator']);
        $container->setParameter('curler7_user.open_api.jwt_decorator.user', $config['open_api']['user']);
        $container->setParameter('curler7_user.open_api.jwt_decorator.password', $config['open_api']['password']);
        $container->setParameter('curler7_user.open_api.jwt_decorator.path', $config['open_api']['path']);
        // Security
        $container->setParameter('curler7_user.security.groups_context_builder.class', $config['security']['groups_context_builder']);
        $container->setParameter('curler7_user.security.authentication_success_handler.class', $config['security']['authentication_success_handler']);
        $container->setParameter('curler7_user.security.user_voter.class', $config['security']['user_voter']);
        $container->setParameter('curler7_user.security.group_voter.class', $config['security']['group_voter']);
        // Serializer
        $container->setParameter('curler7_user.serializer.user_normalizer.class', $config['serializer']['user_normalizer']);
        // Subscriber
        $container->setParameter('curler7_user.subscriber.jwt_subscriber.class', $config['subscriber']['jwt_subscriber']);
        $container->setParameter('curler7_user.subscriber.validate_before_delete.class', $config['subscriber']['validate_before_delete']);
        // Utils
        $container->setParameter('curler7_user.util.canonical_fields_updater.class', $config['util']['canonical_fields_updater']);
        $container->setParameter('curler7_user.util.canonicalizer.class', $config['util']['canonicalizer']);
        $container->setParameter('curler7_user.util.password_updater.class', $config['util']['password_updater']);
        $container->setParameter('curler7_user.util.login_link_sender.class', $config['util']['login_link_sender']);
        $container->setParameter('curler7_user.util.user_spy.class', $config['util']['user_spy']);
        $container->setAlias('curler7_user.util.email_canonicalizer', $config['util']['email_canonicalizer']);
        $container->setAlias('curler7_user.util.username_canonicalizer', $config['util']['username_canonicalizer']);
        // Validator
        $container->setParameter('curler7_user.validator.last_super_admin_user.class', $config['validator']['last_super_admin_user']);

        foreach ([
            'api_platform', 'command', 'controller',
            'event_subscriber', 'open_api',
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
        if ('custom' !== $config['model']['db_driver']) {
            if (isset(self::DOCTRINE_DRIVERS[$config['model']['db_driver']])) {
                $loader->load('model.xml');
                $container->setAlias('curler7_user.doctrine_registry', new Alias(self::DOCTRINE_DRIVERS[$config['model']['db_driver']]['registry'], false));
            } else {
                $loader->load(sprintf('%s.xml', $config['model']['db_driver']));
            }
            $container->setParameter($this->getAlias().'.backend_type_'.$config['model']['db_driver'], true);
        }

        if (isset(self::DOCTRINE_DRIVERS[$config['model']['db_driver']])) {
            $definition = $container->getDefinition('curler7_user.model.object_manager');
            $definition->setFactory([new Reference('curler7_user.doctrine_registry'), 'getManager']);
        }
    }
}
