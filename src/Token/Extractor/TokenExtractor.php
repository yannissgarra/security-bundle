<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Token\Extractor;

use Symfony\Component\HttpFoundation\Request;

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
        return $this->tokenExtractor->extract($request);
    }
}
