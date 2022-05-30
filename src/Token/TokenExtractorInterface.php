<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Token;

use Symfony\Component\HttpFoundation\Request;
use Webmunkeez\SecurityBundle\Exception\TokenExtractionException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface TokenExtractorInterface
{
    public function supports(Request $request): bool;

    /**
     * @throws TokenExtractionException
     */
    public function extract(Request $request): string;
}
