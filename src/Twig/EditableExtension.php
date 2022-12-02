<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Webmunkeez\SecurityBundle\Model\EditableInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class EditableExtension extends AbstractExtension
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('editable', [$this, 'isEditable']),
        ];
    }

    public function isEditable(EditableInterface $object): bool
    {
        if (null === $object->isEditable()) {
            $object->setEditable($this->authorizationChecker->isGranted(EditableInterface::UPDATE, $object));
        }

        return $object->isEditable();
    }
}
