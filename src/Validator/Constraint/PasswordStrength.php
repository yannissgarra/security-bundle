<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class PasswordStrength extends Constraint
{
    public string $message = 'This password is not complex enough.';

    public int $strength;

    public function __construct(int $strength = 3, mixed $options = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);

        // init values
        $this->strength = $strength;
    }
}
