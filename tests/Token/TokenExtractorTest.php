<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Token;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Webmunkeez\SecurityBundle\Exception\TokenExtractionException;
use Webmunkeez\SecurityBundle\Token\TokenExtractor;
use Webmunkeez\SecurityBundle\Token\TokenExtractorInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TokenExtractorTest extends TestCase
{
    /**
     * @var TokenExtractorInterface&MockObject
     */
    private TokenExtractorInterface $randomTokenExtractor;

    private TokenExtractor $extractor;

    protected function setUp(): void
    {
        /** @var TokenExtractorInterface&MockObject $randomTokenExtractor */
        $randomTokenExtractor = $this->getMockBuilder(TokenExtractorInterface::class)->disableOriginalConstructor()->getMock();
        $this->randomTokenExtractor = $randomTokenExtractor;

        $this->extractor = new TokenExtractor();
    }

    public function testAddTokenExtractorShouldSucceed()
    {
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);

        $reflection = new \ReflectionClass(TokenExtractor::class);

        $this->assertCount(1, $reflection->getProperty('tokenExtractors')->getValue($this->extractor));
    }

    public function testSupportsShouldSucceed()
    {
        $this->randomTokenExtractor->method('supports')->willReturn(true);
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);

        $this->assertTrue($this->extractor->supports(new Request()));
    }

    public function testSupportsShouldFail()
    {
        $this->randomTokenExtractor->method('supports')->willReturn(false);
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);

        $this->assertFalse($this->extractor->supports(new Request()));
    }

    public function testExtractShouldSucceed()
    {
        $this->randomTokenExtractor->method('supports')->willReturn(true);
        $this->randomTokenExtractor->method('extract')->willReturn('token');
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);

        $token = $this->extractor->extract(new Request());

        $this->assertSame('token', $token);
    }

    public function testExtractShouldFail()
    {
        $this->expectException(TokenExtractionException::class);

        $this->randomTokenExtractor->method('supports')->willReturn(false);
        $this->randomTokenExtractor->method('extract')->willReturn('');
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);

        $this->extractor->extract(new Request());
    }
}
