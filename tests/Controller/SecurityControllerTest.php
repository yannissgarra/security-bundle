<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Controller\SecurityController;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Repository\UserRepository;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class SecurityControllerTest extends WebTestCase
{
    public function testLoginSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::USER_ID_1);

        $sessionCookie = $client->getCookieJar()->get($client->getKernel()->getContainer()->getParameter('webmunkeez_security.cookie.name'));

        $this->assertNotNull($sessionCookie);
    }

    public function testLogoutSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::USER_ID_1);
        $client->request('GET', SecurityController::LOGOUT_ROUTE_URI);

        $sessionCookie = $client->getCookieJar()->get($client->getKernel()->getContainer()->getParameter('webmunkeez_security.cookie.name'));

        $this->assertNull($sessionCookie);
    }

    public function testProtectedAdminSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::USER_ID_1);
        $client->request('GET', SecurityController::PROTECTED_ADMIN_ROUTE_URI);

        $this->assertTrue(true);
    }

    public function testProtectedAdminFailAnonymous(): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', SecurityController::PROTECTED_ADMIN_ROUTE_URI);
    }

    public function testProtectedAdminFailUser(): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::USER_ID_2);
        $client->request('GET', SecurityController::PROTECTED_ADMIN_ROUTE_URI);
    }

    public function testProtectedUserSuccessAdmin(): void
    {
        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::USER_ID_1);
        $client->request('GET', SecurityController::PROTECTED_USER_ROUTE_URI);

        $this->assertTrue(true);
    }

    public function testProtectedUserSuccessUser(): void
    {
        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::USER_ID_2);
        $client->request('GET', SecurityController::PROTECTED_USER_ROUTE_URI);

        $this->assertTrue(true);
    }

    public function testProtectedUserFail(): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', SecurityController::PROTECTED_USER_ROUTE_URI);
    }

    public function testUnprotectedSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', SecurityController::UNPROTECTED_ROUTE_URI);

        $this->assertTrue(true);
    }
}
