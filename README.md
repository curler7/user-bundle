Usage
============

### Composer
``` json
# composer.json
"repositories": [
    {
        "type": "vcs",
        "url":  "git@github.com:curler7/user-bundle.git"
    }
]
```
**Note:**
> Need to add github token to repository, because it's private

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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Curler7\UserBundle\Model\User as BaseUser;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'app_user')]
class User extends BaseUser
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    #[ORM\ManyToMany(targetEntity: Group::class)]
    #[ORM\JoinTable(name: 'app_users_groups')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\JoinColumn(name: 'group_ud', referencedColumnName: 'id')]
    protected Collection|array $groups;
}
```


#### Group
``` php
# App/Entity/Group.php
<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Curler7\UserBundle\Model\Group as BaseGroup;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'app_group')]
class Group extends BaseGroup
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
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