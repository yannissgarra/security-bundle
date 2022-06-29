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
use Webmunkeez\SecurityBundle\Authorization\AuthorizationChecker;
use Webmunkeez\SecurityBundle\Authorization\AuthorizationCheckerAwareInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class WebmunkeezSecurityExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('authenticator.php');
        $loader->load('authorization.php');
        $loader->load('event_listener.php');
        $loader->load('http.php');
        $loader->load('jwt.php');
        $loader->load('token.php');
        $loader->load('validator.php');
        $loader->load('voter.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $tokenAuthenticatorDefinition = $container->getDefinition(TokenAuthenticator::class);
        $tokenAuthenticatorDefinition->replaceArgument(2, new Reference($config['user_provider']['id']));

        $container->setParameter('webmunkeez_security.cookie.name', $config['cookie']['name']);
        $container->setParameter('webmunkeez_security.jwt.public_key_path', $config['jwt']['public_key_path']);
        $container->setParameter('webmunkeez_security.jwt.secret_key_path', $config['jwt']['secret_key_path']);
        $container->setParameter('webmunkeez_security.jwt.pass_phrase', $config['jwt']['pass_phrase']);
        $container->setParameter('webmunkeez_security.jwt.token_ttl', $config['jwt']['token_ttl']);

        $container->registerForAutoconfiguration(AuthorizationCheckerAwareInterface::class)
            ->addMethodCall('setAuthorizationChecker', [new Reference(AuthorizationChecker::class)]);
    }

    public function prepend(ContainerBuilder $container)
    {
        // define default config for security
        $container->prependExtensionConfig('security', [
            'enable_authenticator_manager' => true,
            'password_hashers' => $this->definePasswordHashers($container->getParameter('kernel.environment')),
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

    private function definePasswordHashers(string $environment): array
    {
        if ('test' === $environment) {
            // By default, password hashers are resource intensive and take time. This is
            // important to generate secure password hashes. In tests however, secure hashes
            // are not important, waste resources and increase test times. The following
            // reduces the work factor to the lowest possible values.
            return [
                PasswordAuthenticatedUserInterface::class => [
                    'algorithm' => 'auto',
                    'cost' => 4, // Lowest possible value for bcrypt
                    'time_cost' => 3, // Lowest possible value for argon
                    'memory_cost' => 10, // Lowest possible value for argon,
                ],
            ];
        }

        return [
            PasswordAuthenticatedUserInterface::class => 'auto',
        ];
    }
}
