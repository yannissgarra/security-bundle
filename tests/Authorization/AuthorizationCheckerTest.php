<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Authorization;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface as CoreAuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webmunkeez\SecurityBundle\Authorization\AuthorizationChecker;
use Webmunkeez\SecurityBundle\Authorization\AuthorizationCheckerInterface;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Entity\User;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class AuthorizationCheckerTest extends TestCase
{
    /**
     * @var CoreAuthorizationCheckerInterface&MockObject
     **/
    private CoreAuthorizationCheckerInterface $coreAuthorizationChecker;

    private AuthorizationCheckerInterface $authorizationChecker;

    protected function setUp(): void
    {
        /** @var CoreAuthorizationCheckerInterface&MockObject $coreAuthorizationChecker */
        $coreAuthorizationChecker = $this->getMockBuilder(CoreAuthorizationCheckerInterface::class)->disableOriginalConstructor()->getMock();
        $this->coreAuthorizationChecker = $coreAuthorizationChecker;

        $this->authorizationChecker = new AuthorizationChecker($this->coreAuthorizationChecker);
    }

    public function testDenyAccessUnlessGrantedWithUserShouldSucceed(): void
    {
        $this->coreAuthorizationChecker->method('isGranted')->willReturn(true);

        $this->expectNotToPerformAssertions();

        $user = new User('id', 'role', 'hello@yannissgarra.com', '@Password2!');

        $this->authorizationChecker->denyAccessUnlessGranted('view', $user);
    }

    public function testValidateWithoutTitleShouldFail(): void
    {
        $this->coreAuthorizationChecker->method('isGranted')->willReturn(false);

        $user = new User('id', 'role', 'hello@yannissgarra.com', '@Password2!');

        try {
            $this->authorizationChecker->denyAccessUnlessGranted('delete', $user);
        } catch (AccessDeniedException $e) {
            $this->assertCount(1, $e->getAttributes());
            $this->assertSame('delete', $e->getAttributes()[0]);
            $this->assertSame($user, $e->getSubject());

            return;
        }

        $this->fail();
    }
}
