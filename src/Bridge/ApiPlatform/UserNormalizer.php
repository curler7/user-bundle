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
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Gunnar Suwe <suwe@smart-media.design>
 */
class UserNormalizer implements ContextAwareDenormalizerInterface, SerializerAwareInterface
{
    use DenormalizerAwareTrait;

    public function __construct(
        private DenormalizerInterface $decorated,
        private CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        private PasswordUpdaterInterface $passwordUpdater,
        private string $resourceClass
    ) {}

    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    public function denormalize($data, $type, $format = null, array $context = []): mixed
    {
        /** @var UserInterface $user */
        $user = $this->denormalizer->denormalize($data, $type, $format, $context);

        if ($user instanceof UserInterface) {

            $user->setUsernameCanonical($this->canonicalFieldsUpdater->canonicalizeUsername($data['username']));
            $user->setEmailCanonical($this->canonicalFieldsUpdater->canonicalizeEmail($data['email']));
            $this->passwordUpdater->hashPassword($user);

            if ($data['username'] ?? null && $data['username'] !== $context['previous_data']) {
                $user->setUsernameCanonical($this->canonicalFieldsUpdater->canonicalizeUsername($data['username']));
            }
            if ($data['email'] ?? null && $data['email'] !== $context['previous_data']) {
                $user->setEmailCanonical($this->canonicalFieldsUpdater->canonicalizeEmail($data['email']));
            }
            if ($data['plainPassword'] ?? null) {
                $this->passwordUpdater->hashPassword($user);
            }
        }

        return $user;
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        if($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}
