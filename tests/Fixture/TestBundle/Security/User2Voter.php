<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Webmunkeez\SecurityBundle\Test\Fixture\TestBundle\Repository\UserRepository;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class User2Voter extends Voter
{
    public const VIEW = 'view';

    protected function supports(string $attribute, $subject): bool
    {
        if (false === in_array($attribute, [self::VIEW])) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (UserRepository::DATA['user-2']['id'] === $user->getUserIdentifier()) {
            return true;
        }

        return false;
    }
}
