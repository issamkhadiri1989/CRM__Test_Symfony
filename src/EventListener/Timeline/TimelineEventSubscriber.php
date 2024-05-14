<?php

declare(strict_types=1);

namespace App\EventListener\Timeline;

use App\Event\History\AbstractHistoryEvent;
use App\Event\History\CancelledInvitationHistoryEvent;
use App\Event\History\InvitationValidationHistoryEvent;
use App\Event\History\NewInvitationHistoryEvent;
use App\Event\History\ProfileConfirmedHistoryEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TimelineEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NewInvitationHistoryEvent::class => 'onNewInvitationSent',
            CancelledInvitationHistoryEvent::class => 'onCancelledInvitationSent',
            InvitationValidationHistoryEvent::class => 'onInvitationValidation',
            ProfileConfirmedHistoryEvent::class => 'onProfileConfirmed',
        ];
    }

    public function onNewInvitationSent(NewInvitationHistoryEvent $event): void
    {
        // maybe do something else before handling the event.
        $this->handleEvent($event);
    }

    public function onCancelledInvitationSent(CancelledInvitationHistoryEvent $event): void
    {
        // maybe do something else before handling the event.
        $this->handleEvent($event);
    }

    public function onInvitationValidation(InvitationValidationHistoryEvent $event): void
    {
        // maybe do something else before handling the event.
        $this->handleEvent($event);
    }

    public function onProfileConfirmed(ProfileConfirmedHistoryEvent $event): void
    {
        // maybe do something else before handling the event.
        $this->handleEvent($event);
    }

    private function handleEvent(AbstractHistoryEvent $event): void
    {
        $history = $event->buildHistory();
        $history->setHistoryDatetime(new \DateTimeImmutable());

        $this->manager->persist($history);
        $this->manager->flush();
    }
}
