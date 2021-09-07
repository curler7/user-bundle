Usage
============

### Composer
``` json
# composer.json
{
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@github.com:curler7/user-bundle.git"
        }
    ],
    "config": {
        "github-oauth": {
            "github.com": "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"
        }
    
    }
}
```
**Note:**
> Need to add GitHub token to config, because it's private

### Config
``` yaml
# config/packages/curler7.yaml
curler7_user:
    user_class:  ~ # Required
    group_class: ~ # Required
```

### Entity
#### User
``` php
# App/Entity/User.php
<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Curler7\UserBundle\Model\AbstractUser;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'app_user')]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'validation_groups' => ['Default', 'post'],
            'security_post_denormalize' => 'is_granted("POST", object)'
        ],
        'register' => [
            'method' => 'POST',
            'path' => '/users/register',
            'validation_groups' => ['Default', 'register'],
        ],
        'login_link' => [
            'method' => 'POST',
            'path' => '/users/login-link',
            'validation_groups' => ['Default', 'login_link'],
            'controller' => 'curler7_user.controller.login_link',
        ],
    ],
    itemOperations: [
        'get' => [
            'security' => 'is_granted("GET", object)'
        ],
        'put' => [
            'validation_groups' => ['Default', 'put'],
            'security' => 'is_granted("PUT", object)'
        ],
        'delete' => [
            'validation_groups' => ['delete'],
            'security' => 'is_granted("DELETE", object)'
        ],
    ],
    order: ['username' => 'ASC'],
)]
class User extends AbstractUser
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ApiProperty(identifier: true)]
    protected UuidInterface $id;

    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'app_users_groups')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    protected Collection|array $groups;
}
```


#### Group
``` php
# App/Entity/Group.php
<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Curler7\UserBundle\Model\AbstractGroup;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'app_group')]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'validation_groups' => ['Default', 'post'],
            'security_post_denormalize' => 'is_granted("POST", object)'
        ],
    ],
    itemOperations: [
        'get' => [
            'security' => 'is_granted("GET", object)'
        ],
        'put' => [
            'validation_groups' => ['Default', 'put'],
            'security' => 'is_granted("PUT", object)'
        ],
        'delete' => [
            'validation_groups' => ['delete'],
            'security' => 'is_granted("DELETE", object)'
        ],
    ],
    order: ['name' => 'ASC'],
)]
class Group extends AbstractGroup
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ApiProperty(identifier: true)]
    protected UuidInterface $id;
}
```

### Config Reference
``` yaml
# config/packages/curler7.yaml
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
        user_registration:    Curler7\UserBundle\Util\UserRegistration
        user_spy:             Curler7\UserBundle\Util\UserSpy
    subscriber:
        jwt_subscriber:       Curler7\UserBundle\EventSubscriber\JWTSubscriber
        validate_before_delete: Curler7\UserBundle\EventSubscriber\ValidateBeforeDeleteSubscriber
    controller:
        login_link:           Curler7\UserBundle\Controller\LoginLinkController
    validator:
        last_super_admin_user: Curler7\UserBundle\Validator\LastSuperAdminUserValidator

```