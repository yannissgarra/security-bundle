<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Entity;

use Webmunkeez\SecurityBundle\Model\UserAwareInterface;
use Webmunkeez\SecurityBundle\Model\UserInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class UserAware implements UserAwareInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
