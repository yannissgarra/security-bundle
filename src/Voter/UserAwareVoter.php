<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Webmunkeez\SecurityBundle\Model\UserAwareInterface;
use Webmunkeez\SecurityBundle\Model\UserInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class UserAwareVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (false === in_array($attribute, [UserAwareInterface::CREATE, UserAwareInterface::READ, UserAwareInterface::UPDATE, UserAwareInterface::DELETE])) {
            return false;
        }

        // only vote on object that implements `UserAwareInterface`
        if (!$subject instanceof UserAwareInterface) {
            return false;
        }

        return true;
    }

    /**
     * @param UserAwareInterface $subject // you know it thanks to `supports()`
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if (null === $subject->getUser()) {
            // if user aware has no user, allow access
            return true;
        }

        return $subject->getUser()->getId()->equals($user->getId());

        throw new \LogicException('This code should not be reached!');
    }
}
