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

namespace Curler7\UserBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class Curler7UserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $this->addRegisterMappingPass($container);
    }

    private function addRegisterMappingPass(ContainerBuilder $container)
    {
        if (class_exists(DoctrineOrmMappingsPass::class)) {
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createXmlMappingDriver(
                    [realpath(__DIR__ . '/../config/doctrine_mapping') => 'Curler7\UserBundle\Model'],
                    ['curler7_user.model_manager_name'],
                    'curler7_user.backend_type_orm'
                )
            );
        }
    }
}
