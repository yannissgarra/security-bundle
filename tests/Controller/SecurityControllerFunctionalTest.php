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
final class SecurityControllerFunctionalTest extends WebTestCase
{
    public function testLoginShouldSucceed(): void
    {
        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-1']['id']);

        $sessionCookie = $client->getCookieJar()->get($client->getKernel()->getContainer()->getParameter('webmunkeez_security.cookie.name'));

        $this->assertNotNull($sessionCookie);
    }

    public function testLogoutShouldSucceed(): void
    {
        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-1']['id']);
        $client->request('GET', SecurityController::LOGOUT_ROUTE_URI);

        $sessionCookie = $client->getCookieJar()->get($client->getKernel()->getContainer()->getParameter('webmunkeez_security.cookie.name'));

        $this->assertNull($sessionCookie);
    }

    public function testProtectedAdminWithAnonymousShouldFail(): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', SecurityController::PROTECTED_ADMIN_ROUTE_URI);
    }

    public function testProtectedAdminWithUserShouldFail(): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-2']['id']);
        $client->request('GET', SecurityController::PROTECTED_ADMIN_ROUTE_URI);
    }

    public function testProtectedAdminWithAdminShouldSucceed(): void
    {
        $this->expectNotToPerformAssertions();

        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-1']['id']);
        $client->request('GET', SecurityController::PROTECTED_ADMIN_ROUTE_URI);
    }

    public function testProtectedUserWithAnonymousShouldFail(): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', SecurityController::PROTECTED_USER_ROUTE_URI);
    }

    public function testProtectedUserWithUserShouldSucceed(): void
    {
        $this->expectNotToPerformAssertions();

        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-2']['id']);
        $client->request('GET', SecurityController::PROTECTED_USER_ROUTE_URI);
    }

    public function testProtectedUserWithAdminShouldSucceed(): void
    {
        $this->expectNotToPerformAssertions();

        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-1']['id']);
        $client->request('GET', SecurityController::PROTECTED_USER_ROUTE_URI);
    }

    public function testProtectedUser1ThanksToAuthorizationCheckerWithAnonymousShouldFail(): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', SecurityController::PROTECTED_USER1_THANKS_TO_AUTHORIZATION_CHECKER_ROUTE_URI);
    }

    public function testProtectedUser1ThanksToAuthorizationCheckerWithUserShouldFail(): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-2']['id']);
        $client->request('GET', SecurityController::PROTECTED_USER1_THANKS_TO_AUTHORIZATION_CHECKER_ROUTE_URI);
    }

    public function testProtectedUSer1ThanksToAuthorizationCheckerWithAdminShouldSucceed(): void
    {
        $this->expectNotToPerformAssertions();

        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-1']['id']);
        $client->request('GET', SecurityController::PROTECTED_USER1_THANKS_TO_AUTHORIZATION_CHECKER_ROUTE_URI);
    }

    public function testUnprotectedWithAnonymousShouldSucceed(): void
    {
        $this->expectNotToPerformAssertions();

        $client = static::createClient();
        $client->request('GET', SecurityController::UNPROTECTED_ROUTE_URI);
    }

    public function testUnprotectedWithUserShouldSucceed(): void
    {
        $this->expectNotToPerformAssertions();

        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-2']['id']);
        $client->request('GET', SecurityController::UNPROTECTED_ROUTE_URI);
    }

    public function testUnprotectedWithAdminShouldSucceed(): void
    {
        $this->expectNotToPerformAssertions();

        $client = static::createClient();
        $client->request('GET', SecurityController::LOGIN_ROUTE_URI.'/'.UserRepository::DATA['user-1']['id']);
        $client->request('GET', SecurityController::UNPROTECTED_ROUTE_URI);
    }
}
