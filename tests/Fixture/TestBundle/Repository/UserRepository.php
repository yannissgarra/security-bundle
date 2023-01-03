<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Repository;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Provider\UserProviderInterface;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model\User;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class UserRepository implements UserProviderInterface
{
    public const DATA = [
        'user-1' => [
            'id' => 'f22ecc77-c093-44e9-90f6-82fb1143337c',
            'role' => 'ROLE_GOD',
            'email' => 'user1@example.com',
            'password' => '@Password2!',
        ],
        'user-2' => [
            'id' => '71a60b1c-ab59-40d9-9ae6-fc0830e6b46f',
            'role' => 'ROLE_USER',
            'email' => 'user2@example.com',
            'password' => '@Password2!',
        ],
    ];

    /**
     * @return array<string, User>
     */
    public static function users(): array
    {
        $users = [];

        foreach (self::DATA as $data) {
            $users[$data['id']] = new User(Uuid::fromString($data['id']), $data['role'], $data['email'], $data['password']);
        }

        return $users;
    }

    public function load(string $identifier): UserInterface
    {
        if (true === array_key_exists($identifier, self::users())) {
            return self::users()[$identifier];
        }

        throw new UserNotFoundException();
    }
}
