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
trait EditableTrait
{
    private ?bool $editable = null;

    public function isEditable(): ?bool
    {
        return $this->editable;
    }

    public function setEditable(?bool $editable): static
    {
        $this->editable = $editable;

        return $this;
    }
}
