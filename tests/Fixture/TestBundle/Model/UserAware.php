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

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class UserAware implements UserAwareInterface
{
    use EditableTrait;

    private ?User $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
