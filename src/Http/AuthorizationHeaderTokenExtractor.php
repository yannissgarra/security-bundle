<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Http;

use Symfony\Component\HttpFoundation\Request;
use Webmunkeez\SecurityBundle\Exception\TokenExtractionException;
use Webmunkeez\SecurityBundle\Token\TokenExtractorInterface;

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
        if (true === $this->supports($request)) {
            return substr($request->headers->get('Authorization'), 7);
        }

        throw new TokenExtractionException();
    }
}
