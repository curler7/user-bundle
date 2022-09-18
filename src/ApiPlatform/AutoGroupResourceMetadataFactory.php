<?php

declare(strict_types=1);

namespace Curler7\UserBundle\ApiPlatform;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Gunnar Suwe
 */
class AutoGroupResourceMetadataFactory implements SerializerContextBuilderInterface
{
    public function __construct(protected SerializerContextBuilderInterface $decorated)
    {}

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        return $context;

        /*
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
        */
    }

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
