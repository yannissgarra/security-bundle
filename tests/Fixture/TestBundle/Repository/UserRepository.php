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
use Webmunkeez\SecurityBundle\Provider\UserProviderInterface;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Entity\User;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class UserRepository implements UserProviderInterface
{
    final public const USER_ID_1 = 'f22ecc77-c093-44e9-90f6-82fb1143337c';
    final public const USER_ID_2 = '71a60b1c-ab59-40d9-9ae6-fc0830e6b46f';

    /**
     * @return array<string, User>
     */
    public static function users(): array
    {
        return [
            self::USER_ID_1 => new User(self::USER_ID_1, 'ROLE_GOD'),
            self::USER_ID_2 => new User(self::USER_ID_2, 'ROLE_USER'),
        ];
    }

    public function load(string $identifier): UserInterface
    {
        if (true === array_key_exists($identifier, self::users())) {
            return self::users()[$identifier];
        }

        throw new UserNotFoundException();
    }
}
