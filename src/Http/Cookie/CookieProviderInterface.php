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
interface CookieProviderInterface
{
    public function create(string $token): Cookie;
}
