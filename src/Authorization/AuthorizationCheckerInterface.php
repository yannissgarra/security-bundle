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
interface AuthorizationCheckerInterface
{
    /**
     * @param string|array<string> $attribute
     *
     * @throws AccessDeniedException
     */
    public function denyAccessUnlessGranted(string|array $attribute, mixed $subject = null): void;
}
