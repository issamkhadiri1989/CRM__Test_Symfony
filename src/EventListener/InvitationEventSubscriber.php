<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\NewInvitationEvent;
use App\Notifier\NotifierInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InvitationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly NotifierInterface $notifier)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NewInvitationEvent::class => 'onNewInvitationPlaced',
        ];
    }

    public function onNewInvitationPlaced(NewInvitationEvent $event): void
    {
        $invitation = $event->getInvitation();

        $this->notifier->sendInvitation($invitation);
    }
}
