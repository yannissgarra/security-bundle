<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\SecurityBundle\Authorization\AuthorizationChecker;
use Webmunkeez\SecurityBundle\Authorization\AuthorizationCheckerInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(AuthorizationChecker::class)
            ->args([service('security.authorization_checker')])

        ->set(AuthorizationCheckerInterface::class)

        ->alias(AuthorizationCheckerInterface::class, AuthorizationChecker::class);
};
