<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class User implements UserInterface
{
    private string $id;
    private string $role;

    public function __construct(string $id, string $role)
    {
        $this->id = $id;
        $this->role = $role;
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
    }
}
