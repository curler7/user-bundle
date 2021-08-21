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

namespace Curler7\UserBundle\Bridge\ApiPlatform;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserResourcePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->getParameter('curler7_user.api-platform')) {
            return;
        }

        $this->generateApiResourceCache($container, 'user_resource.yaml', 'User.yaml');

        if ($container->hasParameter('curler7_user.model.group.class')) {
            $this->generateApiResourceCache($container, 'group_resource.yaml', 'Group.yaml');
        }
    }

    private function generateApiResourceCache(ContainerBuilder $container, $template, $path): void
    {
        $path = sprintf('%s/%s', $container->getParameter('kernel.cache_dir'), $path);
        $cache = new ConfigCache($path, $container->getParameter('kernel.debug'));

        if (!$cache->isFresh()) {
            $template = sprintf('%s/../../../config/template/%s', __DIR__, $template);
            $contents = file_get_contents($template);
            $contents = strtr($contents, [
                '%curler7_user.model.user.class%'  => $container->getParameter('curler7_user.model.user.class'),
                '%curler7_user.model.group.class%' => $container->getParameter('curler7_user.model.group.class'),
            ]);

            $resources = [new FileResource($template)];
            $cache->write($contents, $resources);
        }
    }
}
