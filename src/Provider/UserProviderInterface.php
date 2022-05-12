<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Provider;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface UserProviderInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @throws UserNotFoundException
     */
    public function load(string $identifier, array $data = []): UserInterface;
}
