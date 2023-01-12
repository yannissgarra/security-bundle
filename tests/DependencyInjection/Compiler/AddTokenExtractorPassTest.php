<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Webmunkeez\SecurityBundle\DependencyInjection\Compiler\AddTokenExtractorPass;
use Webmunkeez\SecurityBundle\Token\TokenExtractor;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class AddTokenExtractorPassTest extends TestCase
{
    private AddTokenExtractorPass $pass;

    private ContainerBuilder $container;

    private Definition $managerDefinition;

    protected function setUp(): void
    {
        $this->pass = new AddTokenExtractorPass();
        $this->container = new ContainerBuilder();
        $this->managerDefinition = new Definition();

        $this->container->setDefinition(TokenExtractor::class, $this->managerDefinition);
    }

    public function testProcessWithPrioritizedTokenExtractorsShouldSucceed(): void
    {
        $tokenExtractor1 = new Definition();
        $tokenExtractor1->setTags(['webmunkeez_security.token_extractor' => [
                [
                    'priority' => 0,
                ],
            ],
        ]);
        $this->container->setDefinition('token_extractor_one', $tokenExtractor1);

        $tokenExtractor2 = new Definition();
        $tokenExtractor2->setTags([
            'webmunkeez_security.token_extractor' => [
                [
                    'priority' => 10,
                ],
            ],
        ]);
        $this->container->setDefinition('token_extractor_two', $tokenExtractor2);

        $this->pass->process($this->container);

        $methodCalls = $this->managerDefinition->getMethodCalls();

        $this->assertCount(2, $methodCalls);
        $this->assertEqualsCanonicalizing(['addTokenExtractor', [new Reference('token_extractor_two')]], $methodCalls[0]);
        $this->assertEqualsCanonicalizing(['addTokenExtractor', [new Reference('token_extractor_one')]], $methodCalls[1]);
    }
}
