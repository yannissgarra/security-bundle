<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Authenticator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Authenticator\TokenAuthenticator;
use Webmunkeez\SecurityBundle\Exception\TokenDecodingException;
use Webmunkeez\SecurityBundle\Provider\UserProviderInterface;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Repository\UserRepository;
use Webmunkeez\SecurityBundle\Token\TokenEncoderInterface;
use Webmunkeez\SecurityBundle\Token\TokenExtractorInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TokenAuthenticatorTest extends TestCase
{
    /**
     * @var TokenEncoderInterface&MockObject
     */
    private TokenEncoderInterface $tokenEncoder;

    /**
     * @var TokenExtractorInterface&MockObject
     */
    private TokenExtractorInterface $tokenExtractor;

    /**
     * @var UserProviderInterface&MockObject
     */
    private UserProviderInterface $userProvider;

    protected function setUp(): void
    {
        /** @var TokenEncoderInterface&MockObject $tokenEncoder */
        $tokenEncoder = $this->getMockBuilder(TokenEncoderInterface::class)->disableOriginalConstructor()->getMock();
        $this->tokenEncoder = $tokenEncoder;

        /** @var TokenExtractorInterface&MockObject $tokenExtractor */
        $tokenExtractor = $this->getMockBuilder(TokenExtractorInterface::class)->disableOriginalConstructor()->getMock();
        $this->tokenExtractor = $tokenExtractor;

        /** @var UserProviderInterface&MockObject $userProvider */
        $userProvider = $this->getMockBuilder(UserProviderInterface::class)->disableOriginalConstructor()->getMock();
        $this->userProvider = $userProvider;
    }

    public function testSupportsShouldSucceed(): void
    {
        $this->tokenExtractor->method('supports')->willReturn(true);

        $tokenAuthenticator = new TokenAuthenticator($this->tokenEncoder, $this->tokenExtractor, $this->userProvider);

        $this->assertTrue($tokenAuthenticator->supports(new Request()));
    }

    public function testSupportsShouldFail(): void
    {
        $this->tokenExtractor->method('supports')->willReturn(false);

        $tokenAuthenticator = new TokenAuthenticator($this->tokenEncoder, $this->tokenExtractor, $this->userProvider);

        $this->assertFalse($tokenAuthenticator->supports(new Request()));
    }

    public function testAuthenticateShouldSucceed(): void
    {
        $this->tokenExtractor->method('extract')->willReturn('token');
        $this->tokenEncoder->method('decode')->willReturn(Uuid::fromString(UserRepository::DATA['user-1']['id']));

        $tokenAuthenticator = new TokenAuthenticator($this->tokenEncoder, $this->tokenExtractor, $this->userProvider);

        $passport = $tokenAuthenticator->authenticate(new Request());

        $this->assertInstanceOf(SelfValidatingPassport::class, $passport);

        $userBadge = null;

        foreach ($passport->getBadges() as $badge) {
            if ($badge instanceof UserBadge) {
                $userBadge = $badge;
            }
        }

        $this->assertNotNull($userBadge);
        $this->assertSame(UserRepository::DATA['user-1']['id'], $userBadge->getUserIdentifier());
    }

    public function testAuthenticateShouldThrowException(): void
    {
        $this->tokenExtractor->method('extract')->willReturn('token');
        $this->tokenEncoder->method('decode')->willThrowException(new TokenDecodingException());

        $tokenAuthenticator = new TokenAuthenticator($this->tokenEncoder, $this->tokenExtractor, $this->userProvider);

        $this->expectException(AuthenticationException::class);

        $tokenAuthenticator->authenticate(new Request());
    }
}
