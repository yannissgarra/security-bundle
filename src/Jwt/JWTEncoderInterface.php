<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Jwt;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface JWTEncoderInterface
{
    public function encode(string $identifier): string;

    /**
     * @return array<string, mixed>
     */
    public function decode(string $token): array;
}
