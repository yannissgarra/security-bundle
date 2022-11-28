<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Http\Cookie;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Webmunkeez\SecurityBundle\Exception\TokenExtractionException;
use Webmunkeez\SecurityBundle\Http\Cookie\CookieTokenExtractor;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class CookieTokenExtractorTest extends TestCase
{
    private CookieTokenExtractor $extractor;
    private Request $validRequest;
    private Request $invalidRequest;

    protected function setUp(): void
    {
        $this->extractor = new CookieTokenExtractor('SESSION');

        $this->validRequest = new Request();
        $this->validRequest->cookies->set('SESSION', 'token');

        $this->invalidRequest = new Request();
    }

    public function testSupportsWithCookieShouldSucceed(): void
    {
        $this->assertTrue($this->extractor->supports($this->validRequest));
    }

    public function testSupportsWithoutCookieShouldFail(): void
    {
        $this->assertFalse($this->extractor->supports($this->invalidRequest));
    }

    public function testExtractWithCookieShouldSucceed(): void
    {
        $token = $this->extractor->extract($this->validRequest);

        $this->assertSame('token', $token);
    }

    public function testExtractWithoutCookieShouldFail(): void
    {
        $this->expectException(TokenExtractionException::class);

        $this->extractor->extract($this->invalidRequest);
    }
}
