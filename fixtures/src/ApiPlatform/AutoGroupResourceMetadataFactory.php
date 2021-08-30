<?php

declare(strict_types=1);

namespace App\ApiPlatform;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\ResourceMetadata;
use JetBrains\PhpStorm\Pure;

/**
 * @author Gunnar Suwe
 */
class AutoGroupResourceMetadataFactory implements ResourceMetadataFactoryInterface
{
    public function __construct(protected ResourceMetadataFactoryInterface $decorated)
    {}

    public function create(string $resourceClass): ResourceMetadata
    {
        $resourceMetadata = $this->decorated->create($resourceClass);

        $resourceMetadata = $resourceMetadata->withItemOperations(
            $this->updateContextOnOperations(
                $resourceMetadata->getItemOperations(),
                $resourceMetadata->getShortName(),
                true
            )
        );

        return $resourceMetadata->withCollectionOperations(
            $this->updateContextOnOperations(
                $resourceMetadata->getCollectionOperations(),
                $resourceMetadata->getShortName(),
                false
            )
        );
    }

    #[Pure]
    protected function updateContextOnOperations(array $operations, string $shortName, bool $isItem): array
    {
        foreach ($operations as $operationName => $operationOptions) {
            foreach ([
                'normalization_context' => true,
                'denormalization_context' => false
            ] as $context => $normalization) {
                $operationOptions[$context] = $operationOptions[$context] ?? [];
                $operationOptions[$context]['groups'] = $operationOptions[$context]['groups'] ?? [];
                $operationOptions[$context]['groups'] = array_unique(array_merge(
                    $operationOptions[$context]['groups'],
                    $this->getDefaultGroups(strtolower($shortName), $normalization, $isItem, $operationName)
                ));
            }

            $operations[$operationName] = $operationOptions;
        }

        return $operations;
    }

    protected function getDefaultGroups(string $shortName, bool $normalization, bool $isItem, string $operationName): array
    {
        /*
        * {class}
        * {class}:{read/write}
        * {class}:{item/collection}:{read/write}
        * {class}:{get/post/put/delete/patch}
        */

        return [
            $shortName,
            sprintf('%s:%s', $shortName, $normalization ? 'read' : 'write'),
            sprintf('%s:%s:%s', $shortName, $isItem ? 'item' : 'collection', $normalization ? 'read' : 'write'),
            sprintf('%s:%s', $shortName, $operationName),
        ];
    }
}
