<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Jwt;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Webmunkeez\SecurityBundle\Jwt\JWTEncoder;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class JWTEncoderTest extends KernelTestCase
{
    public function testEncode()
    {
        static::bootKernel();

        $jwtEncoder = new JWTEncoder(
            static::$kernel->getContainer()->getParameter('webmunkeez_security.jwt.secret_key_path'),
            static::$kernel->getContainer()->getParameter('webmunkeez_security.jwt.public_key_path'),
            static::$kernel->getContainer()->getParameter('webmunkeez_security.jwt.pass_phrase'),
            static::$kernel->getContainer()->getParameter('webmunkeez_security.jwt.token_ttl')
        );
        $token = $jwtEncoder->encode('identifier');

        $decodedToken = $jwtEncoder->decode($token);

        $this->assertEquals('identifier', $decodedToken['identifier']);
    }
}
