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
use Symfony\Component\HttpFoundation\Cookie;
use Webmunkeez\SecurityBundle\Exception\CookieProvidingException;
use Webmunkeez\SecurityBundle\Http\Cookie\CookieProvider;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class CookieProviderTest extends TestCase
{
    public function testCreateShouldSucceed(): void
    {
        $cookieProvider = new CookieProvider('SESSION', '1 year');
        $cookie = $cookieProvider->create('token');

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertSame('SESSION', $cookie->getName());
        $this->assertSame('token', $cookie->getValue());
        $this->assertSame((new \DateTime())->modify('+1 year')->getTimestamp(), $cookie->getExpiresTime());
        $this->assertSame('/', $cookie->getPath());
        $this->assertNull($cookie->getDomain());
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertFalse($cookie->isRaw());
        $this->assertSame(Cookie::SAMESITE_STRICT, $cookie->getSameSite());
    }

    public function testCreateWithWrongFormatTTLShouldThrowException(): void
    {
        $cookieProvider = new CookieProvider('SESSION', 'ttl');

        $this->expectException(CookieProvidingException::class);

        $cookieProvider->create('token');
    }
}
