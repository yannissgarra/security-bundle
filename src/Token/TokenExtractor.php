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
final class TokenExtractor implements TokenExtractorInterface
{
    /**
     * @var array<TokenExtractorInterface>
     */
    private array $tokenExtractors = [];

    private TokenExtractorInterface $tokenExtractor;

    public function addTokenExtractor(TokenExtractorInterface $tokenExtractor)
    {
        $this->tokenExtractors[] = $tokenExtractor;
    }

    public function supports(Request $request): bool
    {
        foreach ($this->tokenExtractors as $tokenExtractor) {
            if ($tokenExtractor->supports($request)) {
                $this->tokenExtractor = $tokenExtractor;

                return true;
            }
        }

        return false;
    }

    public function extract(Request $request): string
    {
        if (true === $this->supports($request)) {
            return $this->tokenExtractor->extract($request);
        }

        throw new TokenExtractionException();
    }
}
