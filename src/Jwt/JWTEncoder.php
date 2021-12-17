<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Jwt;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class JWTEncoder implements JWTEncoderInterface
{
    private string $jwtSecretKeyPath;
    private string $jwtPublicKeyPath;
    private string $jwtPassPhrase;
    private string $jwtTokenTTL;

    public function __construct(string $jwtSecretKeyPath, string $jwtPublicKeyPath, string $jwtPassPhrase, string $jwtTokenTTL)
    {
        $this->jwtSecretKeyPath = $jwtSecretKeyPath;
        $this->jwtPublicKeyPath = $jwtPublicKeyPath;
        $this->jwtPassPhrase = $jwtPassPhrase;
        $this->jwtTokenTTL = $jwtTokenTTL;
    }

    public function encode(string $identifier): string
    {
        $privateKey = \openssl_pkey_get_private(
            \file_get_contents($this->jwtSecretKeyPath),
            $this->jwtPassPhrase);

        $payload = [
            'exp' => (new \DateTime())->modify('+'.$this->jwtTokenTTL)->getTimestamp(),
            'iat' => (new \DateTime())->getTimestamp(),
            'identifier' => $identifier,
        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }

    public function decode(string $token): array
    {
        return (array) JWT::decode($token, new Key(\file_get_contents($this->jwtPublicKeyPath), 'RS256'));
    }
}
