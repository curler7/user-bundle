Config Reference
============

``` yaml
# config/packages/curler7_user.yaml
curler7_user:
    model:
        db_driver:            orm
        user_class:           ~ # Required
        group_class:          ~ # Required
        model_manager_name:   default
        group_manager:        Curler7\UserBundle\Manager\GroupManager
        user_manager:         Curler7\UserBundle\Manager\UserManager
        object_manager:       Doctrine\Persistence\ObjectManager
    config:
        resource_user:        true
        resource_group:       true
        serializer_user:      true
        serializer_group:     true
        validation_user:      true
        validation_group:     true
        storage_validation_user: true
        storage_validation_group: true
        login_link_post:      true
        login_link_register:  true
        login_link_share:     true
        operation_login_link: true
        operation_registration: true
    api_platform:
        auto_group_resource_metadata_factory: Curler7\UserBundle\ApiPlatform\AutoGroupResourceMetadataFactory
    security:
        groups_context_builder: Curler7\UserBundle\Serializer\GroupsContextBuilder
        authentication_success_handler: Curler7\UserBundle\Security\Authentication\AuthenticationSuccessHandler
        user_voter:           Curler7\UserBundle\Security\Voter\UserVoter
        group_voter:          Curler7\UserBundle\Security\Voter\GroupVoter
    serializer:
        user_normalizer:      Curler7\UserBundle\Serializer\UserNormalizer
    open_api:
        jwt_decorator:        Curler7\UserBundle\OpenApi\JwtDecorator
        user:                 user
        password:             pass
        path:                 /api/login_check
    command:
        create_user:          Curler7\UserBundle\Command\CreateUserCommand
    util:
        canonical_fields_updater: Curler7\UserBundle\Util\CanonicalFieldsUpdater
        canonicalizer:        Curler7\UserBundle\Util\Canonicalizer
        password_updater:     Curler7\UserBundle\Util\PasswordUpdater
        email_canonicalizer:  curler7_user.util.canonicalizer
        username_canonicalizer: curler7_user.util.canonicalizer
        login_link_sender:    Curler7\UserBundle\Util\LoginLinkSender
        user_spy:             Curler7\UserBundle\Util\UserSpy
    subscriber:
        jwt_subscriber:       Curler7\UserBundle\EventSubscriber\JWTSubscriber
        validate_before_delete: Curler7\UserBundle\EventSubscriber\ValidateBeforeDeleteSubscriber
    controller:
        login_link:           Curler7\UserBundle\Controller\LoginLinkController
    validator:
        last_super_admin_user: Curler7\UserBundle\Validator\LastSuperAdminUserValidator
```