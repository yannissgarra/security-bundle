<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Twig;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Entity\User;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Entity\UserAware;
use Webmunkeez\SecurityBundle\Twig\EditableExtension;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class EditableExtensionTest extends TestCase
{
    /**
     * @var AuthorizationCheckerInterface&MockObject
     **/
    private AuthorizationCheckerInterface $authorizationChecker;

    private EditableExtension $extension;

    protected function setUp(): void
    {
        /** @var AuthorizationCheckerInterface&MockObject $authorizationChecker */
        $authorizationChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->disableOriginalConstructor()->getMock();
        $this->authorizationChecker = $authorizationChecker;

        $this->extension = new EditableExtension($this->authorizationChecker);
    }

    public function testIsEditableShouldSucceed(): void
    {
        $this->authorizationChecker->method('isGranted')->willReturn(true);

        $userAware = new UserAware(new User(Uuid::v4(), 'role', 'hello@yannissgarra.com', '@Password2!'));

        $editable = $this->extension->isEditable($userAware);

        $this->assertTrue($editable);
    }
}
