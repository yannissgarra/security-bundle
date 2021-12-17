<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\SecurityBundle\Token\Extractor\AuthorizationHeaderTokenExtractor;
use Webmunkeez\SecurityBundle\Token\Extractor\CookieTokenExtractor;
use Webmunkeez\SecurityBundle\Token\Extractor\TokenExtractor;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(TokenExtractor::class)
        ->set(AuthorizationHeaderTokenExtractor::class)
            ->tag('webmunkeez_security.token_extractor', ['priority' => 20])
        ->set(CookieTokenExtractor::class)
            ->args([param('webmunkeez_security.cookie.name')])
            ->tag('webmunkeez_security.token_extractor', ['priority' => 10]);
};
