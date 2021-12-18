<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Token\Extractor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Webmunkeez\SecurityBundle\Exception\ExtractException;
use Webmunkeez\SecurityBundle\Token\Extractor\AuthorizationHeaderTokenExtractor;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class AuthorizationHeaderTokenExtractorTest extends TestCase
{
    private AuthorizationHeaderTokenExtractor $extractor;
    private Request $validRequest;
    private Request $invalidRequest;

    protected function setUp(): void
    {
        $this->extractor = new AuthorizationHeaderTokenExtractor();

        $this->validRequest = new Request();
        $this->validRequest->headers->set('Authorization', 'Bearer token');

        $this->invalidRequest = new Request();
    }

    public function testSupportsSuccess()
    {
        $this->assertTrue($this->extractor->supports($this->validRequest));
    }

    public function testSupportsFail()
    {
        $this->assertFalse($this->extractor->supports($this->invalidRequest));
    }

    public function testExtractSuccess()
    {
        $token = $this->extractor->extract($this->validRequest);

        $this->assertEquals('token', $token);
    }

    public function testExtractFail()
    {
        $this->expectException(ExtractException::class);

        $token = $this->extractor->extract($this->invalidRequest);
    }
}
