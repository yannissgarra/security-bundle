<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Action;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmunkeez\SecurityBundle\Model\UserInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
trait UserAwareActionTrait
{
    protected TokenStorageInterface $tokenStorage;

    public function setTokenStorage(TokenStorageInterface $tokenStorage): void
    {
        $this->tokenStorage = $tokenStorage;
    }

    protected function getUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();

        return null !== $token ? $token->getUser() : null;
    }
}
