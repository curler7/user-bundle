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

use Curler7\UserBundle\Model\UserInterface;
use Curler7\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Curler7\UserBundle\Util\PasswordUpdaterInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
final class UserNormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED = 'ALREADY_CALLED';

    public function __construct(
        private CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        private PasswordUpdaterInterface $passwordUpdater,
        private string $resourceClass
    ) {}

    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return $this->resourceClass === $type && !isset($context[self::ALREADY_CALLED]);
    }

    public function denormalize($data, $type, $format = null, array $context = []): UserInterface
    {
        $context[self::ALREADY_CALLED] = true;

        /** @var UserInterface $user */
        $user = $this->denormalizer->denormalize($data, $type, $format, $context);

        if (($data['username'] ?? null) !== ($context['previous_data'] ?? null)) {
            $user->setUsernameCanonical($this->canonicalFieldsUpdater->canonicalizeUsername($data['username']));
        }
        if (($data['email'] ?? null) !== ($context['previous_data'] ?? null)) {
            $user->setEmailCanonical($this->canonicalFieldsUpdater->canonicalizeEmail($data['email']));
        }
        if (isset($data['password'])) {
            $this->passwordUpdater->hashPassword($user);
        }

        return $user;
    }
}
