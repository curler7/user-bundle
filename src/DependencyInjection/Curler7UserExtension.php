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
        $container->prependExtensionConfig('api_platform', [
            'mapping' => [
                'paths' => [
                    $container->getParameter('kernel.cache_dir').'/curler7/user_bundle/api_resources',
                ],
            ],
        ]);
    }

    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        $container->setParameter('curler7_user.model.user.class', $config['user_class']);
        $container->setParameter('curler7_user.model.group.class', $config['group_class']);
        $container->setParameter('curler7_user.model_manager_name', $config['model_manager_name']);
        $container->setParameter('curler7_user.api_platform', $config['api_platform']);

        $container->setAlias('curler7_user.util.email_canonicalizer', $config['service']['email_canonicalizer']);
        $container->setAlias('curler7_user.util.username_canonicalizer', $config['service']['username_canonicalizer']);

        $loader->load('util.xml');
        $loader->load('command.xml');

        $this->loadDbDriver($loader, $container, $config);

        $container->setParameter('curler7_user.storage', $config['db_driver']);

        if ($config['api_platform']) {
            $loader->load('api_platform.xml');
        }
    }

    /**
     * @throws \Exception
     */
    private function loadDbDriver(XmlFileLoader $loader, ContainerBuilder $container, $config)
    {
        if ('custom' !== $config['db_driver']) {
            if (self::DOCTRINE_DRIVERS[$config['db_driver']] ?? null) {
                $loader->load('doctrine.xml');
                $container->setAlias('curler7_user.doctrine_registry', new Alias(self::DOCTRINE_DRIVERS[$config['db_driver']]['registry'], false));
            } else {
                $loader->load(sprintf('%s.xml', $config['db_driver']));
            }
            $container->setParameter($this->getAlias().'.backend_type_'.$config['db_driver'], true);
        }

        if (self::DOCTRINE_DRIVERS[$config['db_driver']] ?? null) {
            $definition = $container->getDefinition('curler7_user.object_manager');
            $definition->setFactory([new Reference('curler7_user.doctrine_registry'), 'getManager']);
        }
    }
}
