<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Serializer\Normalizer;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Webmunkeez\SecurityBundle\Model\EditableInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class EditableNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param EditableInterface $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        // avoid circular reference
        $context[spl_object_id($object).'.'.self::class.'.already_called'] = true;

        if (null === $object->isEditable()) {
            $object->setEditable($this->authorizationChecker->isGranted(EditableInterface::UPDATE, $object));
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (!$data instanceof EditableInterface) {
            return false;
        }

        // avoid circular reference
        if (
            true === isset($context[spl_object_id($data).'.'.self::class.'.already_called'])
            && true === $context[spl_object_id($data).'.'.self::class.'.already_called']
        ) {
            return false;
        }

        return true;
    }
}
