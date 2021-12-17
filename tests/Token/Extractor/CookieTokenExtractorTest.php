<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Token\Extractor;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Webmunkeez\SecurityBundle\Token\Extractor\CookieTokenExtractor;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class CookieTokenExtractorTest extends KernelTestCase
{
    private CookieTokenExtractor $extractor;
    private Request $validRequest;
    private Request $invalidRequest;

    protected function setUp(): void
    {
        static::bootKernel();

        $this->extractor = new CookieTokenExtractor(static::$kernel->getContainer()->getParameter('webmunkeez_security.cookie.name'));

        $this->validRequest = new Request();
        $this->validRequest->cookies->set(static::$kernel->getContainer()->getParameter('webmunkeez_security.cookie.name'), 'token');

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
        $token = $this->extractor->extract($this->invalidRequest);

        $this->assertEquals('', $token);
    }
}
