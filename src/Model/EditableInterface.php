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
interface EditableInterface
{
    public const CREATE = 'CREATE';
    public const READ = 'READ';
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';

    public function isEditable(): ?bool;

    public function setEditable(?bool $editable): static;
}
