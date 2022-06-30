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

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface UserAwareActionInterface
{
    public function setTokenStorage(TokenStorageInterface $tokenStorage): void;
}
