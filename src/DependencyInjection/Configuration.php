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

use App\OpenApi\JwtDecorator;
use Curler7\UserBundle\ApiPlatform\AutoGroupResourceMetadataFactory;
use Curler7\UserBundle\Command\CreateUserCommand;
use Curler7\UserBundle\Manager\GroupManager;
use Curler7\UserBundle\Manager\UserManager;
use Curler7\UserBundle\Serializer\GroupsContextBuilder;
use Curler7\UserBundle\Serializer\UserNormalizer;
use Curler7\UserBundle\Util\CanonicalFieldsUpdater;
use Curler7\UserBundle\Util\Canonicalizer;
use Curler7\UserBundle\Util\PasswordUpdater;
use Curler7\UserBundle\Validator\LastSuperAdminUser;
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
                ->booleanNode('doctrine_mapping')->defaultValue(true)->end()
                ->booleanNode('serialization')->defaultValue(true)->end()
                ->booleanNode('validation')->defaultValue(true)->end()
            ->end();

        $this->addServiceSection($rootNode);
        $this->addJwtDecoratorSection($rootNode);

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
                            ->scalarNode('auto_group_resource_metadata_factory')->defaultValue(AutoGroupResourceMetadataFactory::class)->end()
                            ->scalarNode('groups_context_builder')->defaultValue(GroupsContextBuilder::class)->end()
                            ->scalarNode('jwt_decorator')->defaultValue(JwtDecorator::class)->end()
                            ->scalarNode('command_create_user')->defaultValue(CreateUserCommand::class)->end()
                            ->scalarNode('group_manager')->defaultValue(GroupManager::class)->end()
                            ->scalarNode('user_manager')->defaultValue(UserManager::class)->end()
                            ->scalarNode('object_manager')->defaultValue(ObjectManager::class)->end()
                            ->scalarNode('canonical_fields_updater')->defaultValue(CanonicalFieldsUpdater::class)->end()
                            ->scalarNode('canonicalizer')->defaultValue(Canonicalizer::class)->end()
                            ->scalarNode('password_updater')->defaultValue(PasswordUpdater::class)->end()
                            ->scalarNode('email_canonicalizer')->defaultValue('curler7_user.util.canonicalizer')->end()
                            ->scalarNode('username_canonicalizer')->defaultValue('curler7_user.util.canonicalizer')->end()
                            ->scalarNode('validator_last_super_admin_user')->defaultValue(LastSuperAdminUser::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addJwtDecoratorSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('jwt_decorator')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('user')->defaultValue('user')->end()
                            ->scalarNode('password')->defaultValue('pass')->end()
                            ->scalarNode('path')->defaultValue('/api/login_check')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
