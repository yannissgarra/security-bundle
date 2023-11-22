<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model;

use Webmunkeez\SecurityBundle\Model\EditableTrait;
use Webmunkeez\SecurityBundle\Model\UserAwareInterface;
use Webmunkeez\SecurityBundle\Model\UserInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class UserAware implements UserAwareInterface
{
    use EditableTrait;

    /**
     * @var array<UserInterface>
     */
    private array $users;

    /**
     * @param array<UserInterface> $users
     */
    public function __construct(array $users = [])
    {
        $this->users = $users;
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}
