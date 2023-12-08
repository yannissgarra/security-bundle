<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\SecurityBundle\Validator\Constraint\EmailValidator;
use Webmunkeez\SecurityBundle\Validator\Constraint\PasswordStrengthValidator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(EmailValidator::class)
            ->tag('validator.constraint_validator')

        ->set(PasswordStrengthValidator::class)
            ->tag('validator.constraint_validator');
};
