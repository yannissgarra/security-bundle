<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Voter;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Model\UserAwareInterface;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Entity\User;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Entity\UserAware;
use Webmunkeez\SecurityBundle\Voter\UserAwareVoter;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class UserAwareVoterTest extends TestCase
{
    /**
     * @var TokenInterface&MockObject
     **/
    private TokenInterface $token;

    private \ReflectionMethod $supportsMethod;
    private \ReflectionMethod $voteOnAttribute;

    protected function setUp(): void
    {
        /** @var TokenInterface&MockObject $token */
        $token = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $this->token = $token;

        $voterClass = new \ReflectionClass(UserAwareVoter::class);

        $this->supportsMethod = $voterClass->getMethod('supports');
        $this->supportsMethod->setAccessible(true);

        $this->voteOnAttribute = $voterClass->getMethod('voteOnAttribute');
        $this->voteOnAttribute->setAccessible(true);
    }

    public function testSupportsShouldSucceed(): void
    {
        $userAware = new UserAware(new User(Uuid::v4(), 'role', 'email', 'password'));

        $supports = $this->supportsMethod->invokeArgs(new UserAwareVoter(), [UserAwareInterface::READ, $userAware]);

        $this->assertTrue($supports);
    }

    public function testSupportsWithNotUserAwareShouldFail(): void
    {
        $user = new User(Uuid::v4(), 'role', 'email', 'password');

        $supports = $this->supportsMethod->invokeArgs(new UserAwareVoter(), [UserAwareInterface::READ, $user]);

        $this->assertFalse($supports);
    }

    public function testSupportsWithWrongAttributeShouldFail(): void
    {
        $userAware = new UserAware(new User(Uuid::v4(), 'role', 'email', 'password'));

        $supports = $this->supportsMethod->invokeArgs(new UserAwareVoter(), ['WrongAttribute', $userAware]);

        $this->assertFalse($supports);
    }

    public function testVoteOnAttributeShouldSucceed(): void
    {
        $user = new User(Uuid::v4(), 'role', 'email', 'password');

        $this->token->method('getUser')->willReturn($user);

        $userAware = new UserAware($user);

        $vote = $this->voteOnAttribute->invokeArgs(new UserAwareVoter(), [UserAwareInterface::READ, $userAware, $this->token]);

        $this->assertTrue($vote);
    }

    public function testVoteOnAttributeWithNullUserShouldSucceed(): void
    {
        $user = new User(Uuid::v4(), 'role', 'email', 'password');

        $this->token->method('getUser')->willReturn($user);

        $userAware = new UserAware();

        $vote = $this->voteOnAttribute->invokeArgs(new UserAwareVoter(), [UserAwareInterface::READ, $userAware, $this->token]);

        $this->assertTrue($vote);
    }

    public function testVoteOnAttributeWithNoTokenUserShouldFail(): void
    {
        $this->token->method('getUser')->willReturn(null);

        $userAware = new UserAware(new User(Uuid::v4(), 'role', 'email', 'password'));

        $vote = $this->voteOnAttribute->invokeArgs(new UserAwareVoter(), [UserAwareInterface::READ, $userAware, $this->token]);

        $this->assertFalse($vote);
    }

    public function testVoteOnAttributeWithDifferentTokenUserShouldFail(): void
    {
        $this->token->method('getUser')->willReturn(new User(Uuid::v4(), 'role', 'email', 'password'));

        $userAware = new UserAware(new User(Uuid::v4(), 'role', 'email', 'password'));

        $vote = $this->voteOnAttribute->invokeArgs(new UserAwareVoter(), [UserAwareInterface::READ, $userAware, $this->token]);

        $this->assertFalse($vote);
    }
}
