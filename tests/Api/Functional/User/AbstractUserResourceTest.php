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

namespace Curler7\UserBundle\Tests\Api\Functional\User;

use App\Entity\User;
use Curler7\ApiTestBundle\Tester\AbstractApiTestCase;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
abstract class AbstractUserResourceTest extends AbstractApiTestCase
{
    protected const NAME = 'User';
    protected const URI = '/users';
    protected const CLASSNAME = User::class;
}