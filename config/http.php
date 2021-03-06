<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\SecurityBundle\Http\AuthorizationHeaderTokenExtractor;
use Webmunkeez\SecurityBundle\Http\Cookie\CookieProvider;
use Webmunkeez\SecurityBundle\Http\Cookie\CookieProviderInterface;
use Webmunkeez\SecurityBundle\Http\Cookie\CookieTokenExtractor;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(CookieProvider::class)
            ->args([param('webmunkeez_security.cookie.name'), param('webmunkeez_security.jwt.token_ttl')])

        ->alias(CookieProviderInterface::class, CookieProvider::class)

        ->set(AuthorizationHeaderTokenExtractor::class)
            ->tag('webmunkeez_security.token_extractor', ['priority' => 20])

        ->set(CookieTokenExtractor::class)
            ->args([param('webmunkeez_security.cookie.name')])
            ->tag('webmunkeez_security.token_extractor', ['priority' => 10]);
};
