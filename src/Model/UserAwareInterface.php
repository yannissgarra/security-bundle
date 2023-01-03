<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Model;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface UserAwareInterface extends EditableInterface
{
    public function getUser(): ?UserInterface;
}
