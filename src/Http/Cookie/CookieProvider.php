<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Http\Cookie;

use Symfony\Component\HttpFoundation\Cookie;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class CookieProvider implements CookieProviderInterface
{
    private string $jwtTokenTTL;
    private string $cookieName;

    public function __construct(string $jwtTokenTTL, string $cookieName)
    {
        $this->jwtTokenTTL = $jwtTokenTTL;
        $this->cookieName = $cookieName;
    }

    public function create(string $token): Cookie
    {
        return new Cookie(
            $this->cookieName, // name
            $token, // value
            (new \DateTime())->modify('+'.$this->jwtTokenTTL)->getTimestamp(), // expire
            '/', // path
            null, // domain
            true, // secure
            true, // httpOnly
            false, // raw
            Cookie::SAMESITE_LAX, // sameSite
        );
    }
}
