<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Authenticator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Webmunkeez\SecurityBundle\Exception\InvalidTokenException;
use Webmunkeez\SecurityBundle\Jwt\JWTEncoderInterface;
use Webmunkeez\SecurityBundle\Provider\UserProviderInterface;
use Webmunkeez\SecurityBundle\Token\Extractor\TokenExtractorInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TokenAuthenticator extends AbstractAuthenticator
{
    private JWTEncoderInterface $jwtEncoder;
    private TokenExtractorInterface $tokenExtractor;
    private UserProviderInterface $userProvider;

    public function __construct(JWTEncoderInterface $jwtEncoder, TokenExtractorInterface $tokenExtractor, UserProviderInterface $userProvider)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->tokenExtractor = $tokenExtractor;
        $this->userProvider = $userProvider;
    }

    public function supports(Request $request): ?bool
    {
        return $this->tokenExtractor->supports($request);
    }

    public function authenticate(Request $request): Passport
    {
        try {
            $token = $this->tokenExtractor->extract($request);
            $decodedToken = $this->jwtEncoder->decode($token);

            $identifier = $decodedToken['identifier'];

            return new SelfValidatingPassport(new UserBadge($identifier, fn (string $identifier): UserInterface => $this->userProvider->load($identifier)));
        } catch (\Throwable $e) {
            throw new AuthenticationException('', 0, new InvalidTokenException());
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $e): ?Response
    {
        throw new UnauthorizedHttpException('Token', '', $e);
    }
}
