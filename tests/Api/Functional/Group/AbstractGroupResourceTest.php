<?php

/*
 * This file is part of the Curler7ApiTestBundle project.
 *
 * (c) Gunnar Suwe <suwe@smart-media.design>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Curler7\UserBundle\Tests\Api\Functional\Group;

use App\Entity\Group;
use Curler7\ApiTestBundle\Tester\AbstractApiTestCase;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
abstract class AbstractGroupResourceTest extends AbstractApiTestCase
{
    CONST NAME = 'Group';
    const URI = '/groups';
    const CLASSNAME = Group::class;
}