<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Controller;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Action\UserAwareActionInterface;
use Webmunkeez\SecurityBundle\Action\UserAwareActionTrait;
use Webmunkeez\SecurityBundle\Authorization\AuthorizationCheckerAwareInterface;
use Webmunkeez\SecurityBundle\Authorization\AuthorizationCheckerAwareTrait;
use Webmunkeez\SecurityBundle\Http\Cookie\CookieProviderInterface;
use Webmunkeez\SecurityBundle\Model\UserAwareInterface;
use Webmunkeez\SecurityBundle\Model\UserInterface;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model\User;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model\UserAware;
use Webmunkeez\SecurityBundle\Token\TokenEncoderInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class SecurityController implements AuthorizationCheckerAwareInterface, UserAwareActionInterface
{
    use AuthorizationCheckerAwareTrait;
    use UserAwareActionTrait;

    public const LOGIN_ROUTE_URI = '/login';
    public const LOGOUT_ROUTE_URI = '/logout';
    public const PROTECTED_ADMIN_ROUTE_URI = '/protected-admin';
    public const PROTECTED_USER_ROUTE_URI = '/protected-user';
    public const PROTECTED_USER2_THANKS_TO_AUTHORIZATION_CHECKER_ROUTE_URI = '/protected-user2-thanks-to-authorization-checker';
    public const PROTECTED_USER2_OR_ADMIN_THANKS_TO_AUTHORIZATION_CHECKER_ROUTE_URI = '/protected-user2-or-admin-thanks-to-authorization-checker';
    public const UNPROTECTED_ROUTE_URI = '/unprotected';
    public const USER_READ_ROUTE_URI = '/public/user-read';
    public const USER_AWARE_ROUTE_URI = '/public/user-aware/{withUser}';

    private ParameterBagInterface $parameterBag;
    private CookieProviderInterface $cookieProvider;
    private TokenEncoderInterface $tokenEncoder;

    public function __construct(ParameterBagInterface $parameterBag, CookieProviderInterface $cookieProvider, TokenEncoderInterface $tokenEncoder)
    {
        $this->parameterBag = $parameterBag;
        $this->cookieProvider = $cookieProvider;
        $this->tokenEncoder = $tokenEncoder;
    }

    #[Route(self::LOGIN_ROUTE_URI.'/{identifier}')]
    public function login(string $identifier): Response
    {
        $token = $this->tokenEncoder->encode(Uuid::fromString($identifier));

        $response = new Response('Logged in!');
        $response->headers->setCookie($this->cookieProvider->create($token));

        return $response;
    }

    #[Route(self::LOGOUT_ROUTE_URI)]
    public function logout(): Response
    {
        $response = new Response('Logged out!');
        $response->headers->clearCookie($this->parameterBag->get('webmunkeez_security.cookie.name'));

        return $response;
    }

    #[Route(self::PROTECTED_ADMIN_ROUTE_URI)]
    #[IsGranted('ROLE_ADMIN')]
    public function protectedAdmin(): Response
    {
        return new Response('Protected route, authorized for ROLE_ADMIN.');
    }

    #[Route(self::PROTECTED_USER_ROUTE_URI)]
    #[IsGranted('ROLE_USER')]
    public function protectedUser(): Response
    {
        return new Response('Protected route, authorized for ROLE_USER.');
    }

    #[Route(self::PROTECTED_USER2_THANKS_TO_AUTHORIZATION_CHECKER_ROUTE_URI)]
    #[IsGranted('ROLE_USER')]
    public function protectedUser2ThanksToAuthorizationChecker(): Response
    {
        $this->denyAccessUnlessGranted('view');

        return new Response('Protected route, authorized for User 2.');
    }

    #[Route(self::PROTECTED_USER2_OR_ADMIN_THANKS_TO_AUTHORIZATION_CHECKER_ROUTE_URI)]
    #[IsGranted('ROLE_USER')]
    public function protectedUser2OrAdminThanksToAuthorizationChecker(): Response
    {
        $this->denyAccessUnlessGranted(['ROLE_ADMIN', 'view']);

        return new Response('Protected route, authorized for User 2 or Admin.');
    }

    #[Route(self::UNPROTECTED_ROUTE_URI)]
    public function unprotected(): Response
    {
        return new Response('Unprotected route.');
    }

    #[Route(self::USER_READ_ROUTE_URI)]
    public function userRead(): Response
    {
        if (null !== $this->getUser() && $this->getUser() instanceof UserInterface) {
            return new Response('There is a user.');
        }

        return new Response('There is no user.');
    }

    #[Route(self::USER_AWARE_ROUTE_URI)]
    #[IsGranted('ROLE_USER')]
    public function userAware(string $withUser = 'right'): Response
    {
        $userAware = new UserAware();

        if ('right' === $withUser) {
            $userAware = new UserAware($this->getUser());
        }

        if ('wrong' === $withUser) {
            $userAware = new UserAware(new User(Uuid::v4(), 'role', 'hello@yannissgarra.com', '@Password2!'));
        }

        $this->denyAccessUnlessGranted(UserAwareInterface::READ, $userAware);

        return new Response('User aware route');
    }
}
