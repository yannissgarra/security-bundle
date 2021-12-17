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
final class CookieTokenExtractor implements TokenExtractorInterface
{
    private string $cookieName;

    public function __construct(string $cookieName)
    {
        $this->cookieName = $cookieName;
    }

    public function supports(Request $request): bool
    {
        if ($request->cookies->has($this->cookieName)) {
            return true;
        }

        return false;
    }

    public function extract(Request $request): string
    {
        return $request->cookies->get($this->cookieName);
    }
}
