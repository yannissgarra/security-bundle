<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\SecurityBundle\Serializer\Normalizer\EditableNormalizer;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(EditableNormalizer::class)
            ->args([service('security.authorization_checker')])
            ->tag('serializer.normalizer', ['priority' => 10]);
};
