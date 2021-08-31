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
        /*
        $config = (new Processor())->processConfiguration(
            new Configuration(),
            $container->getParameterBag()->resolveValue($container->getExtensionConfig($this->getAlias()))
        );
        */

        $container->prependExtensionConfig('api_platform', [
            'mapping' => [
                'paths' => [
                    __DIR__ . '/../../config/template',
                ],
            ],
        ]);

        $container->prependExtensionConfig('framework', [
            'serializer' => [
                'mapping' => [
                    'paths' => [
                        __DIR__ . '/../../config/serialization',
                    ],
                ],
            ],
            'validation' => [
                'mapping' => [
                    'paths' => [
                        __DIR__ . '/../../config/validation',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = (new Processor())->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        // Config
        $container->setParameter('curler7_user.model.user.class', $config['user_class']);
        $container->setParameter('curler7_user.model.group.class', $config['group_class']);
        $container->setParameter('curler7_user.model_manager_name', $config['model_manager_name']);
        $container->setParameter('curler7_user.api_platform', $config['api_platform']);
        // Normalizer
        $container->setParameter('curler7_user.user_normalizer.class', $config['service']['user_normalizer']);
        $container->setParameter('curler7_user.auto_group_resource_metadata_factory.class', $config['service']['auto_group_resource_metadata_factory']);
        $container->setParameter('curler7_user.groups_context_builder.class', $config['service']['groups_context_builder']);
        $container->setParameter('curler7_user.jwt_decorator.class', $config['service']['jwt_decorator']);

        $container->setParameter('curler7_user.jwt_decorator.user', $config['jwt_decorator']['user']);
        $container->setParameter('curler7_user.jwt_decorator.password', $config['jwt_decorator']['password']);
        $container->setParameter('curler7_user.jwt_decorator.path', $config['jwt_decorator']['path']);
        // Command
        $container->setParameter('curler7_user.command.create_user.class', $config['service']['command_create_user']);
        // Doctrine
        $container->setParameter('curler7_user.group_manager.class', $config['service']['group_manager']);
        $container->setParameter('curler7_user.user_manager.class', $config['service']['user_manager']);
        $container->setParameter('curler7_user.object_manager.class', $config['service']['object_manager']);
        $container->setParameter('curler7_user.storage', $config['db_driver']);
        // Utils
        $container->setParameter('curler7_user.canonical_fields_updater.class', $config['service']['canonical_fields_updater']);
        $container->setParameter('curler7_user.canonicalizer.class', $config['service']['canonicalizer']);
        $container->setParameter('curler7_user.password_updater.class', $config['service']['password_updater']);
        // Validator
        $container->setParameter('curler7_user.validator.last_super_admin_user.class', $config['service']['validator_last_super_admin_user']);

        $container->setAlias('curler7_user.util.email_canonicalizer', $config['service']['email_canonicalizer']);
        $container->setAlias('curler7_user.util.username_canonicalizer', $config['service']['username_canonicalizer']);

        foreach ([
            'util',
            'command',
            'api_platform',
            'auto_group_resource_metadata_factory',
            'groups_context_builder',
            'jwt_decorator',
            // 'validator',
        ] as $file) {
            if ($config['service'][$file] ?? true) {
                $loader->load($file.'.xml');
            }
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
