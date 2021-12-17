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
    final public const CONFIG = [
        'user_provider' => [
            'id' => 'user_provider_id_value',
        ],
        'jwt' => [
            'secret_key_path' => 'jwt_secret_key_path_value',
            'public_key_path' => 'jwt_public_key_path_value',
            'pass_phrase' => 'jwt_pass_phrase_value',
            'token_ttl' => 'jwt_token_ttl_value',
        ],
        'cookie' => [
            'name' => 'cookie_name_value',
        ],
    ];

    public function testConfiguration()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => self::CONFIG]);

        $this->assertEquals(self::CONFIG, $config);
    }

    public function testJwtTtlDefaultConfiguration()
    {
        $config = self::CONFIG;
        unset($config['jwt']['token_ttl']);

        $configTest = self::CONFIG;
        $configTest['jwt']['token_ttl'] = '1 year';

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);

        $this->assertEquals($configTest, $config);
    }

    public function testCookieNameDefaultConfiguration()
    {
        $config = self::CONFIG;
        unset($config['cookie']['name']);

        $configTest = self::CONFIG;
        $configTest['cookie']['name'] = 'SESSION_TOKEN';

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);

        $this->assertEquals($configTest, $config);
    }

    public function testNoConfiguration()
    {
        $this->expectException(InvalidConfigurationException::class);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), []);
    }

    public function testNoProviderConfiguration()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['user_provider']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testNoProviderIdConfiguration()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['user_provider']['id']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testNoJwtConfiguration()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['jwt']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testNoJwtSecretKeyConfiguration()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['jwt']['secret_key_path']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }

    public function testNoJwtPublicKeyConfiguration()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::CONFIG;
        unset($config['jwt']['public_key_path']);

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), ['webmunkeez_security' => $config]);
    }
}
