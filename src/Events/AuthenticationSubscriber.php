<?php

namespace App\Events;

use App\Service\AuthLogService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly AuthLogService $service
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class =>"onLoginSuccess",
            LoginFailureEvent::class =>"onLoginFailure",
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $this->service->logSuccess($event->getUser()->getUserIdentifier(),$event->getRequest()->getClientIp());
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $message = $event->getException()->getMessage();
        if($event->getException() instanceof TooManyLoginAttemptsAuthenticationException){
            $message = "Too many login attempts";
        }

        $this->service->logFailure(
            $event->getRequest()->get('email'),
            $message,
            $event->getRequest()->getClientIp(),
        );

    }
}