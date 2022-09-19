<?php

declare(strict_types=1);

namespace Curler7\UserBundle\ApiPlatform;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;
use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Gunnar Suwe
 */
class AutoGroupResourceMetadataFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        protected ResourceMetadataCollectionFactoryInterface $decorated
    ) {}

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $metadataCollection = $this->decorated->create($resourceClass);

        /** @var Operation $metadata */
        foreach ($metadataCollection->getIterator() as $metadata) {
            $context = $metadata->getNormalizationContext() ?? [];
            $context['groups'] ??= [];
            $context['groups'] = array_unique(array_merge(
                [$context]['groups'],
                $this->getDefaultGroups(strtolower($metadata->getShortName()), true, $metadata->getName())
            ));
            $metadataCollection->append($metadata->withNormalizationContext($context));

            $context = $metadata->getDenormalizationContext() ?? [];
            $context['groups'] ??=  [];
            $context['groups'] = array_unique(array_merge(
                [$context]['groups'],
                $this->getDefaultGroups(strtolower($metadata->getShortName()), false, $metadata->getName())
            ));
            $metadataCollection->append($metadata->withDenormalizationContext($context));
        }

        return $metadataCollection;
    }

    protected function getDefaultGroups(string $shortName, bool $normalization, string $operationName): array
    {
        /*
        * {class}
        * {class}:{read/write}
        * {class}:{get/post/put/delete/patch/...}
        * {class}:{get/post/put/delete/patch/...}:{read/write}
        */

        return [
            $shortName,
            sprintf('%s:%s', $shortName, $normalization ? 'read' : 'write'),
            sprintf('%s:%s', $shortName, $operationName),
            sprintf('%s:%s:%s', $shortName, $operationName, $normalization ? 'read' : 'write'),
        ];
    }
}
