<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Http;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Webmunkeez\SecurityBundle\Exception\TokenExtractionException;
use Webmunkeez\SecurityBundle\Http\AuthorizationHeaderTokenExtractor;

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

    public function testSupportsWithAuthorizationBearerShouldSucceed(): void
    {
        $this->assertTrue($this->extractor->supports($this->validRequest));
    }

    public function testSupportsWithoutAuthorizationBearerShouldFail(): void
    {
        $this->assertFalse($this->extractor->supports($this->invalidRequest));
    }

    public function testExtractWithAuthorizationBearerShouldSucceed(): void
    {
        $token = $this->extractor->extract($this->validRequest);

        $this->assertSame('token', $token);
    }

    public function testExtractWithoutAuthorizationBearerShouldFail(): void
    {
        $this->expectException(TokenExtractionException::class);

        $this->extractor->extract($this->invalidRequest);
    }
}
