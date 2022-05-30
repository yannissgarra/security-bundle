<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\SecurityBundle\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class AccessDeniedExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof AccessDeniedException) {
            $event->setThrowable(new AccessDeniedHttpException($event->getThrowable()->getMessage(), $event->getThrowable(), $event->getThrowable()->getCode()));
        }
    }
}
