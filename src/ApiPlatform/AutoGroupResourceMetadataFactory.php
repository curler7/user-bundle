<?php

declare(strict_types=1);

namespace App\ApiPlatform;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;
use Psr\Log\LoggerInterface;

/**
 * @author Gunnar Suwe
 */
class AutoGroupResourceMetadataFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        protected ResourceMetadataCollectionFactoryInterface $decorated,
        protected LoggerInterface $logger,
    ) {}

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $metadataCollection = $this->decorated->create($resourceClass);

        /** @var ApiResource $item */
        foreach ($metadataCollection->getIterator() as $item) {
            /**
             * @var string $name
             * @var Operation $operation
             */
            foreach ($item->getOperations() ?? [] as $name => $operation) {
                $context = $operation->getNormalizationContext() ?? [];
                $context['groups'] = array_unique(array_merge(
                    [$context]['groups'] ?? [],
                    $this->getDefaultGroups(strtolower($operation->getShortName()), true, $name)
                ));
                $operation->withNormalizationContext($context);

                $context = $operation->getDenormalizationContext() ?? [];
                $context['groups'] = array_unique(array_merge(
                    [$context]['groups'] ?? [],
                    $this->getDefaultGroups(strtolower($operation->getShortName()), false, $name)
                ));
                $operation->withDenormalizationContext($context);

                $this->logger->error('context', $metadataCollection->getOperation($name)->getDenormalizationContext() ?? ['noop']);
            }
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
