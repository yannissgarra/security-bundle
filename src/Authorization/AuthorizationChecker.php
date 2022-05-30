<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Authorization;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface as CoreAuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class AuthorizationChecker implements AuthorizationCheckerInterface
{
    private CoreAuthorizationCheckerInterface $authorizationChecker;

    public function __construct(CoreAuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function denyAccessUnlessGranted(mixed $attribute, mixed $subject = null): void
    {
        if (false === $this->authorizationChecker->isGranted($attribute, $subject)) {
            $exception = new AccessDeniedException();
            $exception->setAttributes($attribute);
            $exception->setSubject($subject);

            throw $exception;
        }
    }
}
