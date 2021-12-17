<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Http\Cookie;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Cookie;
use Webmunkeez\SecurityBundle\Http\Cookie\CookieProvider;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class CookieProviderTest extends KernelTestCase
{
    public function testSuccess()
    {
        static::bootKernel();

        $cookieProvider = new CookieProvider(static::$kernel->getContainer()->getParameter('webmunkeez_security.cookie.name'), static::$kernel->getContainer()->getParameter('webmunkeez_security.jwt.token_ttl'));
        $cookie = $cookieProvider->create('token');

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertEquals(static::$kernel->getContainer()->getParameter('webmunkeez_security.cookie.name'), $cookie->getName());
        $this->assertEquals('token', $cookie->getValue());
        $this->assertEquals((new \DateTime())->modify('+'.static::$kernel->getContainer()->getParameter('webmunkeez_security.jwt.token_ttl'))->getTimestamp(), $cookie->getExpiresTime());
        $this->assertEquals('/', $cookie->getPath());
        $this->assertNull($cookie->getDomain());
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertFalse($cookie->isRaw());
        $this->assertEquals(Cookie::SAMESITE_LAX, $cookie->getSameSite());
    }
}
