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

use Curler7\UserBundle\Bridge\ApiPlatform\UserNormalizer;
use Curler7\UserBundle\Bridge\ORM\GroupManager;
use Curler7\UserBundle\Bridge\ORM\UserManager;
use Curler7\UserBundle\Command\CreateUserCommand;
use Curler7\UserBundle\Util\CanonicalFieldsUpdater;
use Curler7\UserBundle\Util\Canonicalizer;
use Curler7\UserBundle\Util\PasswordUpdater;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class Configuration implements ConfigurationInterface
{
    public const TREE_NAME = 'curler7_user';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::TREE_NAME);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('db_driver')->defaultValue('orm')->end()
                ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('group_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('model_manager_name')->defaultValue('default')->end()
                ->booleanNode('api_platform')->defaultValue(true)->end()
            ->end();

        $this->addServiceSection($rootNode);
        $this->addConfigSection($rootNode);

        return $treeBuilder;
    }

    private function addServiceSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('service')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('user_normalizer')->defaultValue(UserNormalizer::class)->end()
                            ->scalarNode('command_create_user')->defaultValue(CreateUserCommand::class)->end()
                            ->scalarNode('group_manager')->defaultValue(GroupManager::class)->end()
                            ->scalarNode('user_manager')->defaultValue(UserManager::class)->end()
                            ->scalarNode('object_manager')->defaultValue(ObjectManager::class)->end()
                            ->scalarNode('canonical_fields_updater')->defaultValue(CanonicalFieldsUpdater::class)->end()
                            ->scalarNode('canonicalizer')->defaultValue(Canonicalizer::class)->end()
                            ->scalarNode('password_updater')->defaultValue(PasswordUpdater::class)->end()
                            ->scalarNode('email_canonicalizer')->defaultValue('curler7_user.util.canonicalizer')->end()
                            ->scalarNode('username_canonicalizer')->defaultValue('curler7_user.util.canonicalizer')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addConfigSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('config')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('doctrine_mapping_group')->defaultValue(__DIR__.'../../config/doctrine_mapping/AbstractGroup.orm.xml')->end()
                            ->scalarNode('doctrine_mapping_user')->defaultValue(__DIR__.'../../config/doctrine_mapping/AbstractUser.orm.xml')->end()
                            ->scalarNode('serialization_group')->defaultValue(__DIR__.'../../config/serialization/AbstractGroup.yaml')->end()
                            ->scalarNode('serialization_user')->defaultValue(__DIR__.'../../config/serialization/AbstractUser.yaml')->end()
                            ->scalarNode('storage_validation_group')->defaultValue(__DIR__.'../../config/storage_validation/AbstractGroup.yaml')->end()
                            ->scalarNode('storage_validation_user')->defaultValue(__DIR__.'../../config/storage_validation/AbstractUser.yaml')->end()
                            ->scalarNode('template_group_resource')->defaultValue(__DIR__.'../../config/template/group_resource.yaml')->end()
                            ->scalarNode('template_user_resource')->defaultValue(__DIR__.'../../config/template/user_resource.yaml')->end()
                            ->scalarNode('validation_group')->defaultValue(__DIR__.'../../config/validation/AbstractGroup.yaml')->end()
                            ->scalarNode('validation_user')->defaultValue(__DIR__.'../../config/validation/AbstractUser.yaml')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
