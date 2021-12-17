<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Webmunkeez\SecurityBundle\Authenticator\TokenAuthenticator;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class WebmunkeezSecurityExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('authenticators.php');
        $loader->load('http.php');
        $loader->load('jwt.php');
        $loader->load('token_extractors.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $tokenAuthenticatorDefinition = $container->getDefinition(TokenAuthenticator::class);
        $tokenAuthenticatorDefinition->replaceArgument(2, new Reference($config['user_provider']['id']));

        $container->setParameter('webmunkeez_security.jwt.secret_key_path', $config['jwt']['secret_key_path']);
        $container->setParameter('webmunkeez_security.jwt.public_key_path', $config['jwt']['public_key_path']);
        $container->setParameter('webmunkeez_security.jwt.pass_phrase', $config['jwt']['pass_phrase']);
        $container->setParameter('webmunkeez_security.jwt.token_ttl', $config['jwt']['token_ttl']);
        $container->setParameter('webmunkeez_security.cookie.name', $config['cookie']['name']);
    }

    public function prepend(ContainerBuilder $container)
    {
        // define default config for security
        $container->prependExtensionConfig('security', [
            'enable_authenticator_manager' => true,
            'password_hashers' => [
                PasswordAuthenticatedUserInterface::class => 'auto',
            ],
            'firewalls' => [
                'dev' => [
                    'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                    'security' => false,
                ],
                'main' => [
                    'stateless' => true,
                    'custom_authenticators' => [TokenAuthenticator::class],
                ],
            ],
            'role_hierarchy' => [
                'ROLE_GOD' => 'ROLE_ADMIN',
                'ROLE_ADMIN' => 'ROLE_MODERATOR',
                'ROLE_MODERATOR' => 'ROLE_EDITOR',
                'ROLE_EDITOR' => 'ROLE_USER',
            ],
        ]);
    }
}
