<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webmunkeez\SecurityBundle\Token\Extractor\TokenExtractor;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class AddTokenExtractorPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition(TokenExtractor::class)) {
            return;
        }

        $definition = $container->getDefinition(TokenExtractor::class);

        foreach ($this->findAndSortTaggedServices('webmunkeez_security.token_extractor', $container) as $reference) {
            $definition->addMethodCall('addTokenExtractor', [$reference]);
        }
    }
}
