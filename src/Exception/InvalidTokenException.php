<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Exception;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class InvalidTokenException extends RuntimeException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = false === empty($message) ? $message : 'Token is invalid.';

        parent::__construct($message, $code, $previous);
    }
}
