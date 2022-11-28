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
    public const CONFIG = [
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
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => self::CONFIG]);

        $this->assertSame(self::CONFIG['user_provider']['id'], $config['user_provider']['id']);
        $this->assertSame(self::CONFIG['cookie']['name'], $config['cookie']['name']);
        $this->assertSame(self::CONFIG['jwt']['public_key_path'], $config['jwt']['public_key_path']);
        $this->assertSame(self::CONFIG['jwt']['secret_key_path'], $config['jwt']['secret_key_path']);
        $this->assertSame(self::CONFIG['jwt']['pass_phrase'], $config['jwt']['pass_phrase']);
        $this->assertSame(self::CONFIG['jwt']['token_ttl'], $config['jwt']['token_ttl']);
    }

    public function testProcessWithoutJwtTtlShouldSucceed(): void
    {
        $config = self::CONFIG;
        unset($config['jwt']['token_ttl']);

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);

        $this->assertSame(self::CONFIG['user_provider']['id'], $config['user_provider']['id']);
        $this->assertSame(self::CONFIG['cookie']['name'], $config['cookie']['name']);
        $this->assertSame(self::CONFIG['jwt']['public_key_path'], $config['jwt']['public_key_path']);
        $this->assertSame(self::CONFIG['jwt']['secret_key_path'], $config['jwt']['secret_key_path']);
        $this->assertSame(self::CONFIG['jwt']['pass_phrase'], $config['jwt']['pass_phrase']);
        $this->assertSame('1 year', $config['jwt']['token_ttl']);
    }

    public function testProcessWithoutCookieNameShouldSucceed(): void
    {
        $config = self::CONFIG;
        unset($config['cookie']['name']);

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);

        $this->assertSame(self::CONFIG['user_provider']['id'], $config['user_provider']['id']);
        $this->assertSame('SESSION_TOKEN', $config['cookie']['name']);
        $this->assertSame(self::CONFIG['jwt']['public_key_path'], $config['jwt']['public_key_path']);
        $this->assertSame(self::CONFIG['jwt']['secret_key_path'], $config['jwt']['secret_key_path']);
        $this->assertSame(self::CONFIG['jwt']['pass_phrase'], $config['jwt']['pass_phrase']);
        $this->assertSame(self::CONFIG['jwt']['token_ttl'], $config['jwt']['token_ttl']);
    }

    public function testProcessWithoutConfigurationShoulFail(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), []);
    }

    public function testProcessWithoutProviderShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['user_provider']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutProviderIdShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['user_provider']['id']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutJwtShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['jwt']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutJwtPublicKeyShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['jwt']['public_key_path']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutJwtSecretKeyShouldThrowException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['jwt']['secret_key_path']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }
}
