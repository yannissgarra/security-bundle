<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface as CoreUserInterface;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\SecurityBundle\Model\UserInterface;
use Webmunkeez\SecurityBundle\Validator\Constraint\Email;
use Webmunkeez\SecurityBundle\Validator\Constraint\PasswordStrenght;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class User implements CoreUserInterface, UserInterface
{
    private Uuid $id;
    private string $role;

    #[Email]
    private string $email;

    #[PasswordStrenght(strenght: 1)]
    private string $plainPassword;

    public function __construct(Uuid $id, string $role, string $email, string $plainPassword)
    {
        $this->id = $id;
        $this->role = $role;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->id->toRfc4122();
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
    }
}
