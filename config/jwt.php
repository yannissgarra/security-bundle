<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\SecurityBundle\Jwt\JwtTokenEncoder;
use Webmunkeez\SecurityBundle\Token\TokenEncoderInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(JwtTokenEncoder::class)
            ->args([
                param('webmunkeez_security.jwt.public_key_path'),
                param('webmunkeez_security.jwt.secret_key_path'),
                param('webmunkeez_security.jwt.pass_phrase'),
                param('webmunkeez_security.jwt.token_ttl'),
            ])

        ->alias(TokenEncoderInterface::class, JwtTokenEncoder::class);
};
