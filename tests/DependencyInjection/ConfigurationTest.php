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

    public function testProcessWithFullConfigurationShouldSucceed()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => self::CONFIG]);

        $this->assertSame(self::CONFIG, $config);
    }

    public function testProcessWithoutJwtTtlShouldSucceed()
    {
        $config = self::CONFIG;
        unset($config['jwt']['token_ttl']);

        $configTest = self::CONFIG;
        $configTest['jwt']['token_ttl'] = '1 year';

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);

        $this->assertSame($configTest, $config);
    }

    public function testProcessWithoutCookieNameShouldSucceed()
    {
        $config = self::CONFIG;
        unset($config['cookie']['name']);

        $configTest = self::CONFIG;
        $configTest['cookie']['name'] = 'SESSION_TOKEN';

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);

        $this->assertSame($configTest, $config);
    }

    public function testProcessWithoutConfigurationShoulFail()
    {
        $this->expectException(InvalidConfigurationException::class);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), []);
    }

    public function testProcessWithoutProviderShouldFail()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['user_provider']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutProviderIdShouldFail()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['user_provider']['id']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutJwtShouldFail()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['jwt']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutJwtPublicKeyShouldFail()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['jwt']['public_key_path']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testProcessWithoutJwtSecretKeyShouldFail()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['jwt']['secret_key_path']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }
}
