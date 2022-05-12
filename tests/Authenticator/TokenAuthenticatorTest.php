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
use Webmunkeez\SecurityBundle\Authenticator\TokenAuthenticator;
use Webmunkeez\SecurityBundle\Jwt\JWTEncoderInterface;
use Webmunkeez\SecurityBundle\Jwt\JWTPayload;
use Webmunkeez\SecurityBundle\Provider\UserProviderInterface;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Repository\UserRepository;
use Webmunkeez\SecurityBundle\Token\Extractor\TokenExtractorInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TokenAuthenticatorTest extends TestCase
{
    /**
     * @var JWTEncoderInterface&MockObject
     */
    private JWTEncoderInterface $jwtEncoder;

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
        $this->jwtEncoder = $this->getMockBuilder(JWTEncoderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->tokenExtractor = $this->getMockBuilder(TokenExtractorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->userProvider = $this->getMockBuilder(UserProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testSupportsSuccess(): void
    {
        $this->tokenExtractor->method('supports')->willReturn(true);

        $tokenAuthenticator = new TokenAuthenticator($this->jwtEncoder, $this->tokenExtractor, $this->userProvider);

        $this->assertTrue($tokenAuthenticator->supports(new Request()));
    }

    public function testNoSupportsSuccess(): void
    {
        $this->tokenExtractor->method('supports')->willReturn(false);

        $tokenAuthenticator = new TokenAuthenticator($this->jwtEncoder, $this->tokenExtractor, $this->userProvider);

        $this->assertFalse($tokenAuthenticator->supports(new Request()));
    }

    public function testAuthenticateSuccess(): void
    {
        $this->tokenExtractor->method('extract')->willReturn('token');
        $this->jwtEncoder->method('decode')->willReturn(new JWTPayload(UserRepository::DATA['user-1']['id']));

        $tokenAuthenticator = new TokenAuthenticator($this->jwtEncoder, $this->tokenExtractor, $this->userProvider);

        $passport = $tokenAuthenticator->authenticate(new Request());

        $this->assertInstanceOf(SelfValidatingPassport::class, $passport);

        $userBadge = null;

        foreach ($passport->getBadges() as $badge) {
            if ($badge instanceof UserBadge) {
                $userBadge = $badge;
            }
        }

        $this->assertNotNull($userBadge);
        $this->assertEquals(UserRepository::DATA['user-1']['id'], $userBadge->getUserIdentifier());
    }

    public function testAuthenticateFail(): void
    {
        $this->tokenExtractor->method('extract')->willReturn('token');
        $this->jwtEncoder->method('decode')->willThrowException(new \Exception());

        $tokenAuthenticator = new TokenAuthenticator($this->jwtEncoder, $this->tokenExtractor, $this->userProvider);

        $this->expectException(AuthenticationException::class);

        $tokenAuthenticator->authenticate(new Request());
    }
}
