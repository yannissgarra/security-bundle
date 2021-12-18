<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Token\Extractor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Webmunkeez\SecurityBundle\Token\Extractor\TokenExtractor;
use Webmunkeez\SecurityBundle\Token\Extractor\TokenExtractorInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TokenExtractorTest extends TestCase
{
    private TokenExtractor $extractor;
    private Request $request;

    /**
     * @var TokenExtractorInterface&MockObject
     */
    private TokenExtractorInterface $randomTokenExtractor;

    protected function setUp(): void
    {
        $this->extractor = new TokenExtractor();

        $this->request = new Request();

        $this->randomTokenExtractor = $this->getMockBuilder(TokenExtractorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testAddTokenExtractor()
    {
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);

        $reflection = new \ReflectionClass(TokenExtractor::class);

        $this->assertCount(1, $reflection->getProperty('tokenExtractors')->getValue($this->extractor));
    }

    public function testSupportsSuccess()
    {
        $this->randomTokenExtractor->method('supports')->willReturn(true);
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);

        $this->assertTrue($this->extractor->supports($this->request));
    }

    public function testSupportsFail()
    {
        $this->randomTokenExtractor->method('supports')->willReturn(false);
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);

        $this->assertFalse($this->extractor->supports($this->request));
    }

    public function testExtractSuccess()
    {
        $this->randomTokenExtractor->method('supports')->willReturn(true);
        $this->randomTokenExtractor->method('extract')->willReturn('token');
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);
        $this->extractor->supports($this->request);

        $token = $this->extractor->extract($this->request);

        $this->assertEquals('token', $token);
    }

    public function testExtractFail()
    {
        $this->expectError(); // TokenExtractor::$tokenExtractor must not be accessed before initialization

        $this->randomTokenExtractor->method('supports')->willReturn(false);
        $this->randomTokenExtractor->method('extract')->willReturn('');
        $this->extractor->addTokenExtractor($this->randomTokenExtractor);
        $this->extractor->supports($this->request);

        $this->extractor->extract($this->request);
    }
}
