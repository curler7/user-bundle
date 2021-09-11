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
# config/packages/curler7_user.yaml
curler7_user:
  model:
    user_class: App\Entity\User
    group_class: App\Entity\Group
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
use Curler7\UserBundle\Model\AbstractUser;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'app_user')]
class User extends AbstractUser
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
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

use Doctrine\ORM\Mapping as ORM;
use Curler7\UserBundle\Model\AbstractGroup;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity]
#[ORM\Table(name: 'app_group')]
class Group extends AbstractGroup
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;
}
```