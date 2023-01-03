<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Serializer\Normalizer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Serializer\Normalizer\EditableNormalizer;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model\User;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model\UserAware;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class EditableNormalizerTest extends TestCase
{
    public const DATA = [
        'user_aware' => [
            'editable' => true,
        ],
    ];

    /**
     * @var NormalizerInterface&MockObject
     **/
    private NormalizerInterface $coreNormalizer;

    /**
     * @var AuthorizationCheckerInterface&MockObject
     **/
    private AuthorizationCheckerInterface $authorizationChecker;

    private EditableNormalizer $normalizer;

    protected function setUp(): void
    {
        /** @var NormalizerInterface&MockObject $coreNormalizer */
        $coreNormalizer = $this->getMockBuilder(NormalizerInterface::class)->disableOriginalConstructor()->getMock();
        $this->coreNormalizer = $coreNormalizer;

        /** @var AuthorizationCheckerInterface&MockObject $authorizationChecker */
        $authorizationChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->disableOriginalConstructor()->getMock();
        $this->authorizationChecker = $authorizationChecker;

        $this->normalizer = new EditableNormalizer($this->authorizationChecker);
        $this->normalizer->setNormalizer($this->coreNormalizer);
    }

    public function testNormalizeShouldSucceed(): void
    {
        $this->coreNormalizer->method('normalize')->willReturn(self::DATA['user_aware']);
        $this->authorizationChecker->method('isGranted')->willReturn(true);

        $userAware = new UserAware(new User(Uuid::v4(), 'role', 'hello@yannissgarra.com', '@Password2!'));

        $data = $this->normalizer->normalize($userAware);

        $this->assertTrue($data['editable']);
    }
}
