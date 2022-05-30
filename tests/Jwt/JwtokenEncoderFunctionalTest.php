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
use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Exception\TokenDecodingException;
use Webmunkeez\SecurityBundle\Exception\TokenEncodingException;
use Webmunkeez\SecurityBundle\Jwt\JWTTokenEncoder;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Repository\UserRepository;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class JWTTokenEncoderFunctionalTest extends KernelTestCase
{
    private JWTTokenEncoder $tokenEncoder;

    protected function setUp(): void
    {
        $this->tokenEncoder = new JWTTokenEncoder(
            static::getContainer()->getParameter('webmunkeez_security.jwt.public_key_path'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.secret_key_path'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.pass_phrase'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.token_ttl')
        );
    }

    public function testEncodeAndDecodeShouldSucceed()
    {
        $token = $this->tokenEncoder->encode(Uuid::fromString(UserRepository::DATA['user-1']['id']));

        $userId = $this->tokenEncoder->decode($token);

        $this->assertSame(UserRepository::DATA['user-1']['id'], $userId->toRfc4122());
    }

    public function testEncodeWithWrongPrivateKeyPathShouldFail()
    {
        $tokenEncoder = new JWTTokenEncoder(
            static::getContainer()->getParameter('webmunkeez_security.jwt.public_key_path'),
            'secret_key_path',
            static::getContainer()->getParameter('webmunkeez_security.jwt.pass_phrase'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.token_ttl')
        );

        $this->expectException(TokenEncodingException::class);

        $tokenEncoder->encode(Uuid::fromString(UserRepository::DATA['user-1']['id']));
    }

    public function testEncodeWithWrongPassPhraseShouldFail()
    {
        $tokenEncoder = new JWTTokenEncoder(
            static::getContainer()->getParameter('webmunkeez_security.jwt.public_key_path'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.secret_key_path'),
            'pass_phrase',
            static::getContainer()->getParameter('webmunkeez_security.jwt.token_ttl')
        );

        $this->expectException(TokenEncodingException::class);

        $tokenEncoder->encode(Uuid::fromString(UserRepository::DATA['user-1']['id']));
    }

    public function testDecodeWithWrongPublicKeyPathShouldFail()
    {
        $token = $this->tokenEncoder->encode(Uuid::fromString(UserRepository::DATA['user-1']['id']));

        $tokenEncoder = new JWTTokenEncoder(
            'public_key_path',
            static::getContainer()->getParameter('webmunkeez_security.jwt.secret_key_path'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.pass_phrase'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.token_ttl')
        );

        $this->expectException(TokenDecodingException::class);

        $tokenEncoder->decode($token);
    }

    public function testDecodeWithWrongPublicKeyShouldFail()
    {
        $token = $this->tokenEncoder->encode(Uuid::fromString(UserRepository::DATA['user-1']['id']));

        $tokenEncoder = new JWTTokenEncoder(
            static::getContainer()->getParameter('jwt.wrong_public_key_path'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.secret_key_path'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.pass_phrase'),
            static::getContainer()->getParameter('webmunkeez_security.jwt.token_ttl')
        );

        $this->expectException(TokenDecodingException::class);

        $tokenEncoder->decode($token);
    }

    public function testDecodeWithWrongFormatTokenShouldFail()
    {
        $this->expectException(TokenDecodingException::class);

        $this->tokenEncoder->decode('token');
    }

    public function testDecodeWithWrongFormatUserIdShouldFail()
    {
        $this->expectException(TokenDecodingException::class);

        $this->tokenEncoder->decode('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1c2VyX2lkIjoiaWQifQ.PJ1jRFfmR7bJ0pt2dVDsjdCD6g8350vYzzR3Hm-02_e1BUA-ZY6PLnPqOgGgO9_TP1fxrvz7r5rsZbu_ytcDb9poKA1Gfju10QIgc8qoHXIQacHCV1Zga3Wp6S3cPIALTE3mM9hMNs78aAwb-HVYZQD8jZvZVjiPh1i7izRVZoOWyBAqiipeuivXMNTQR0PlcjilY-1-TGs17lOtJ8XFjluI4ULdo6tXQ8VUkabyRjUbmsStfc0Hh15wSkuYSeBvoow4mRnFRX2NwDjuUVvpuzd3qSytl5qgaDeIlBvKHA_GB1cH-Gp_BNJgfqd1l4PaUdeeqOIUn-desuBYGYVbhssJuy7WHfw1KZ0kaFbVZ3Mcw-VvpF78abuCCyq2tjrNOz-Kmrqg946W9Bv8pQSzjPP18UWWtmNqINIk250VnqekPsbyGweVZxqmsqebIkRYDDrb_GIEVEduGChBdiXd_Z2n2enWVVYL-7D_agGNZUfHDXpZcvDHm2ln27ravtsCaXQf5xshQvWKKldNwTmWK8yin-UaaoXqAZK1mAiIp0FCUa63QO6ONt4rEW6VVIRhPtTKchMsq0zl77SEeOk6vI_EFg8mmfC9nfpW7m76jcxWSSTYZyE0eDHCvhK3UvQG-zKnlczh72bmugIox-vwMb2eK2ht3XCdFm2VGEeZfEk');
    }

    public function testDecodeWithWrongSignatureShouldFail()
    {
        $this->expectException(TokenDecodingException::class);

        $this->tokenEncoder->decode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiZjIyZWNjNzctYzA5My00NGU5LTkwZjYtODJmYjExNDMzMzdjIn0.Y-egtEFYxO-95yzlxYAFIxYsv-Y1E7j__tbeKPS6IMI');
    }
}
