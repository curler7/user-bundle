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

namespace Curler7\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class ValidationPass implements CompilerPassInterface
{
    protected const PARAMETER = 'curler7_user.storage';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(self::PARAMETER) ||
            'custom' === $storage = $container->getParameter(self::PARAMETER))
        {
            return;
        }

        foreach (['User', 'Group'] as $value) {
            $container->getDefinition('validator.builder')->addMethodCall('addYamlMapping', [
                sprintf('%s/../../Resources/config/storage-validation/%s.yaml', __DIR__, $value)
            ]);
        }
    }
}
