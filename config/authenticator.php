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
use Webmunkeez\SecurityBundle\Provider\UserProviderInterface;
use Webmunkeez\SecurityBundle\Token\TokenEncoderInterface;
use Webmunkeez\SecurityBundle\Token\TokenExtractorInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(TokenAuthenticator::class)
            ->args([service(TokenEncoderInterface::class), service(TokenExtractorInterface::class), service(UserProviderInterface::class)]);
};
