<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Serializer\Normalizer;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model\User;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model\UserAware;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class EditableNormalizerFunctionalTest extends KernelTestCase
{
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $this->serializer = static::getContainer()->get('serializer');
    }

    public function testNormalizeShouldSucceed(): void
    {
        $user = new User(Uuid::v4(), 'role', 'hello@yannissgarra.com', '@Password2!');
        $userAware = new UserAware($user);

        static::getContainer()->get(TokenStorageInterface::class)->setToken(new TestBrowserToken($user->getRoles(), $user));

        $json = $this->serializer->serialize($userAware, JsonEncoder::FORMAT);

        $this->assertSame('{"user":{"id":"'.$userAware->getUser()->getId()->toRfc4122().'","userIdentifier":"'.$userAware->getUser()->getId()->toRfc4122().'","roles":["role"]},"editable":true}', $json);
    }

    public function testNormalizeWithoutLoggedInUserShouldSucceed(): void
    {
        $user = new User(Uuid::v4(), 'role', 'hello@yannissgarra.com', '@Password2!');
        $userAware = new UserAware($user);

        $json = $this->serializer->serialize($userAware, JsonEncoder::FORMAT);

        $this->assertSame('{"user":{"id":"'.$userAware->getUser()->getId()->toRfc4122().'","userIdentifier":"'.$userAware->getUser()->getId()->toRfc4122().'","roles":["role"]},"editable":false}', $json);
    }

    public function testNormalizeWithWrongLoggedInUserShouldSucceed(): void
    {
        $user1 = new User(Uuid::v4(), 'role', 'hello@yannissgarra.com', '@Password2!');
        $user2 = new User(Uuid::v4(), 'role', 'hello@yannissgarra.com', '@Password2!');
        $userAware = new UserAware($user2);

        static::getContainer()->get(TokenStorageInterface::class)->setToken(new TestBrowserToken($user1->getRoles(), $user1));

        $json = $this->serializer->serialize($userAware, JsonEncoder::FORMAT);

        $this->assertSame('{"user":{"id":"'.$userAware->getUser()->getId()->toRfc4122().'","userIdentifier":"'.$userAware->getUser()->getId()->toRfc4122().'","roles":["role"]},"editable":false}', $json);
    }
}
