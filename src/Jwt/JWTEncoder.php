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
use Webmunkeez\SecurityBundle\Exception\TokenDecodingException;
use Webmunkeez\SecurityBundle\Exception\TokenEncodingException;

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

    public function encode(string $userIdentifier, array $data = []): string
    {
        try {
            $privateKey = \openssl_pkey_get_private(
            \file_get_contents($this->jwtSecretKeyPath),
            $this->jwtPassPhrase);

            $payload = [
                'exp' => (new \DateTime())->modify('+'.$this->jwtTokenTTL)->getTimestamp(),
                'iat' => (new \DateTime())->getTimestamp(),
                'user_identifier' => $userIdentifier,
                'data' => $data,
            ];

            return JWT::encode($payload, $privateKey, 'RS256');
        } catch (\Throwable $e) {
            throw new TokenEncodingException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function decode(string $token): JWTPayload
    {
        try {
            $data = JWT::decode($token, new Key(\file_get_contents($this->jwtPublicKeyPath), 'RS256'));

            return new JWTPayload($data->user_identifier, (array) $data->data);
        } catch (\Throwable $e) {
            throw new TokenDecodingException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
