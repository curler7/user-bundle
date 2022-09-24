<?php

declare(strict_types=1);

namespace Curler7\UserBundle\Serializer;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use Curler7\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;


/**
 * @author Gunnar Suwe
 */
final class GroupsContextBuilder implements SerializerContextBuilderInterface
{
    public function __construct(private SerializerContextBuilderInterface $decorated, private Security $security)
    {}

    /** @throws \ReflectionException */
    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        $context['groups'] ??=  [];
        $context['groups'] = array_unique(array_merge(
            $context['groups'], $this->addDefaultGroups($context, $normalization)
        ));

        return $context;
    }

    /** @throws \ReflectionException */
    private function addDefaultGroups(array $context, bool $normalization): ?array
    {
        if (!$resourceClass = $context['resource_class'] ?? null) {
            return null;
        }

        $shortName = lcfirst((new \ReflectionClass($resourceClass))->getShortName());
        $groups = [
            sprintf(
                '%s',
                $shortName,
            ),
            sprintf(
                '%s:%s',
                $shortName,
                $normalization ? 'read' : 'write',
            ),
            sprintf(
                '%s:%s',
                $shortName,
                explode('_', $context['operation_name'])[substr_count($context['operation_name'], '_') - 1]
            ),
            sprintf(
                '%s:%s:%s',
                $shortName,
                explode('_', $context['operation_name'])[substr_count($context['operation_name'], '_') - 1],
                $normalization ? 'read' : 'write',
            ),
        ];

        /*
         * {role}:{class}
         * {role}:{class}:{read/write}
         * {role}:{class}:{get/post/put/delete/patch/...}
         * {role}:{class}:{get/post/put/delete/patch/...}:{read/write}
         */
        foreach ([
            'super_admin' => UserInterface::ROLE_SUPER_ADMIN,
            'admin' => UserInterface::ROLE_ADMIN,
            'user' => UserInterface::ROLE_DEFAULT
        ] as $key => $role) {
            if ($this->security->isGranted($role)) {
                $groups = array_merge($groups, [
                    sprintf(
                        '%s:%s',
                        $key,
                        $shortName,
                    ),
                    sprintf(
                        '%s:%s:%s',
                        $key,
                        $shortName,
                        $normalization ? 'read' : 'write',
                    ),
                    sprintf(
                        '%s:%s:%s',
                        $key,
                        $shortName,
                        $context['operation_name'],
                    ),
                    sprintf(
                        '%s:%s:%s:%s',
                        $key,
                        $shortName,
                        $context['operation_name'],
                        $normalization ? 'read' : 'write',
                    ),
                ]);
            }
        }

        return $groups;
    }
}
