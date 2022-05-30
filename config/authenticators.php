<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\SecurityBundle\Authenticator\TokenAuthenticator;
use Webmunkeez\SecurityBundle\Jwt\JWTTokenEncoder;
use Webmunkeez\SecurityBundle\Provider\UserProviderInterface;
use Webmunkeez\SecurityBundle\Token\TokenExtractor;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(TokenAuthenticator::class)
            ->args([service(JWTTokenEncoder::class), service(TokenExtractor::class), service(UserProviderInterface::class)]);
};
