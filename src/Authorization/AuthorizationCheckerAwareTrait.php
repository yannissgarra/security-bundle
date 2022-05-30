<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Authorization;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
trait AuthorizationCheckerAwareTrait
{
    protected AuthorizationCheckerInterface $authorizationChecker;

    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @throws AccessDeniedException
     */
    protected function denyAccessUnlessGranted(mixed $attribute, mixed $subject = null): void
    {
        $this->authorizationChecker->denyAccessUnlessGranted($attribute, $subject);
    }
}
