<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Token\Extractor;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class AuthorizationHeaderTokenExtractor implements TokenExtractorInterface
{
    public function supports(Request $request): bool
    {
        if (true === $request->headers->has('Authorization')
            && 1 === preg_match('/Bearer\s(.+)/', $request->headers->get('Authorization'))) {
            return true;
        }

        return false;
    }

    public function extract(Request $request): string
    {
        if (false === $request->headers->has('Authorization')
            || false === preg_match('/Bearer\s(.+)/', $request->headers->get('Authorization'))) {
            return '';
        }

        return substr($request->headers->get('Authorization'), 7);
    }
}
