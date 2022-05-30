<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Token\Encoder;

use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Exception\TokenDecodingException;
use Webmunkeez\SecurityBundle\Exception\TokenEncodingException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface TokenEncoderInterface
{
    /**
     * @throws TokenEncodingException
     */
    public function encode(Uuid $userId): string;

    /**
     * @throws TokenDecodingException
     */
    public function decode(string $token): Uuid;
}
