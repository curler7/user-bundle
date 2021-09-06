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
    db_driver:            orm
    user_class:           ~ # Required
    group_class:          ~ # Required
    model_manager_name:   default
    api_platform:         true
    service:
        email_canonicalizer:    curler7_user.util.canonicalizer
        username_canonicalizer: curler7_user.util.canonicalizer
```