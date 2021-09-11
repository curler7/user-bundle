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

use Curler7\UserBundle\Controller\LoginLinkController;
use Curler7\UserBundle\EventSubscriber\JWTSubscriber;
use Curler7\UserBundle\EventSubscriber\ValidateBeforeDeleteSubscriber;
use Curler7\UserBundle\OpenApi\JwtDecorator;
use Curler7\UserBundle\ApiPlatform\AutoGroupResourceMetadataFactory;
use Curler7\UserBundle\Command\CreateUserCommand;
use Curler7\UserBundle\Manager\GroupManager;
use Curler7\UserBundle\Manager\UserManager;
use Curler7\UserBundle\Security\Authentication\AuthenticationSuccessHandler;
use Curler7\UserBundle\Security\Voter\GroupVoter;
use Curler7\UserBundle\Security\Voter\UserVoter;
use Curler7\UserBundle\Serializer\GroupsContextBuilder;
use Curler7\UserBundle\Serializer\UserNormalizer;
use Curler7\UserBundle\Util\CanonicalFieldsUpdater;
use Curler7\UserBundle\Util\Canonicalizer;
use Curler7\UserBundle\Util\PasswordUpdater;
use Curler7\UserBundle\Util\LoginLinkSender;
use Curler7\UserBundle\Util\UserSpy;
use Curler7\UserBundle\Validator\LastSuperAdminUserValidator;
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

        $this->addModelSection($rootNode);
        $this->addConfigSection($rootNode);
        $this->addApiPlatformSection($rootNode);
        $this->addSecuritySection($rootNode);
        $this->addSerializerSection($rootNode);
        $this->addOpenApiSection($rootNode);
        $this->addCommandSection($rootNode);
        $this->addUtilSection($rootNode);
        $this->addSubscriberSection($rootNode);
        $this->addControllerSection($rootNode);
        $this->addValidatorSection($rootNode);

        return $treeBuilder;
    }

    private function addConfigSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('config')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('resource_user')->defaultValue(true)->end()
                            ->booleanNode('resource_group')->defaultValue(true)->end()
                            ->booleanNode('serializer_user')->defaultValue(true)->end()
                            ->booleanNode('serializer_group')->defaultValue(true)->end()
                            ->booleanNode('validation_user')->defaultValue(true)->end()
                            ->booleanNode('validation_group')->defaultValue(true)->end()
                            ->booleanNode('storage_validation_user')->defaultValue(true)->end()
                            ->booleanNode('storage_validation_group')->defaultValue(true)->end()
                            ->booleanNode('login_link_post')->defaultValue(true)->end()
                            ->booleanNode('login_link_register')->defaultValue(true)->end()
                            ->booleanNode('login_link_share')->defaultValue(true)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addModelSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('model')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('db_driver')->defaultValue('orm')->end()
                            ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('group_class')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('model_manager_name')->defaultValue('default')->end()
                            ->scalarNode('group_manager')->defaultValue(GroupManager::class)->end()
                            ->scalarNode('user_manager')->defaultValue(UserManager::class)->end()
                            ->scalarNode('object_manager')->defaultValue(ObjectManager::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addApiPlatformSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('api_platform')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('auto_group_resource_metadata_factory')->defaultValue(AutoGroupResourceMetadataFactory::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addSerializerSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('serializer')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('user_normalizer')->defaultValue(UserNormalizer::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addSecuritySection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('security')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('groups_context_builder')->defaultValue(GroupsContextBuilder::class)->end()
                            ->scalarNode('authentication_success_handler')->defaultValue(AuthenticationSuccessHandler::class)->end()
                            ->scalarNode('user_voter')->defaultValue(UserVoter::class)->end()
                            ->scalarNode('group_voter')->defaultValue(GroupVoter::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addOpenApiSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('open_api')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('jwt_decorator')->defaultValue(JwtDecorator::class)->end()
                            ->scalarNode('user')->defaultValue('user')->end()
                            ->scalarNode('password')->defaultValue('pass')->end()
                            ->scalarNode('path')->defaultValue('/api/login_check')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addCommandSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('command')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('create_user')->defaultValue(CreateUserCommand::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addUtilSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('util')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('canonical_fields_updater')->defaultValue(CanonicalFieldsUpdater::class)->end()
                            ->scalarNode('canonicalizer')->defaultValue(Canonicalizer::class)->end()
                            ->scalarNode('password_updater')->defaultValue(PasswordUpdater::class)->end()
                            ->scalarNode('email_canonicalizer')->defaultValue('curler7_user.util.canonicalizer')->end()
                            ->scalarNode('username_canonicalizer')->defaultValue('curler7_user.util.canonicalizer')->end()
                            ->scalarNode('login_link_sender')->defaultValue(LoginLinkSender::class)->end()
                            ->scalarNode('user_spy')->defaultValue(UserSpy::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addSubscriberSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('subscriber')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('jwt_subscriber')->defaultValue(JWTSubscriber::class)->end()
                            ->scalarNode('validate_before_delete')->defaultValue(ValidateBeforeDeleteSubscriber::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addControllerSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('controller')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('login_link')->defaultValue(LoginLinkController::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addValidatorSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('validator')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('last_super_admin_user')->defaultValue(LastSuperAdminUserValidator::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
