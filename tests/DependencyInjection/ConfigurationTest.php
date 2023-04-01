<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Webmunkeez\SecurityBundle\DependencyInjection\Configuration;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ConfigurationTest extends TestCase
{
    public const DATA = [
        'user_provider' => [
            'id' => 'user_provider_id_value',
        ],
        'cookie' => [
            'name' => 'cookie_name_value',
        ],
        'jwt' => [
            'public_key_path' => 'jwt_public_key_path_value',
            'secret_key_path' => 'jwt_secret_key_path_value',
            'pass_phrase' => 'jwt_pass_phrase_value',
            'token_ttl' => 'jwt_token_ttl_value',
        ],
    ];

    public function testProcessWithFullConfigurationShouldSucceed(): void
    {
        $processedConfig = (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_security' => self::DATA]);

        $this->assertEqualsCanonicalizing(self::DATA, $processedConfig);
    }

    public function testProcessWithoutJwtTtlShouldSucceed(): void
    {
        $config = self::DATA;
        unset($config['jwt']['token_ttl']);

        $processedConfig = (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);

        $config['jwt']['token_ttl'] = '1 year';

        $this->assertEqualsCanonicalizing($config, $processedConfig);
    }

    public function testProcessWithoutCookieNameShouldSucceed(): void
    {
        $config = self::DATA;
        unset($config['cookie']['name']);

        $processedConfig = (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);

        $config['cookie']['name'] = 'SESSION_TOKEN';

        $this->assertEqualsCanonicalizing($config, $processedConfig);
    }

    public function testProcessWithoutConfigurationShoulFail(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        (new Processor())->processConfiguration(new Configuration(), []);
    }

    public function testProcessWithoutProviderShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::DATA;
        unset($config['user_provider']);

        (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutProviderIdShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::DATA;
        unset($config['user_provider']['id']);

        (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutJwtShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::DATA;
        unset($config['jwt']);

        (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutJwtPublicKeyShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::DATA;
        unset($config['jwt']['public_key_path']);

        (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutJwtSecretKeyShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::DATA;
        unset($config['jwt']['secret_key_path']);

        (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }
}
