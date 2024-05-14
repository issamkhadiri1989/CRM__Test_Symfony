<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use App\Enums\InvitationStatusEnums;
use App\Event\History\InvitationValidationHistoryEvent;
use App\Persister\Invitation\InvitationPersisterInterface;
use App\Security\Handler\Confirmation\InvitationConfirmationProcessor;
use App\Service\Timeline\Dispatcher\TimelineEventTriggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\LoginLinkAuthenticator;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private bool $isLoginLink = false;

    public function __construct(
        private readonly InvitationPersisterInterface $persister,
        private readonly InvitationConfirmationProcessor $processor,
        private readonly TimelineEventTriggerInterface $eventTrigger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => 'onCheckPassportEvent',
            AuthenticationTokenCreatedEvent::class => 'onAuthenticationTokenCreatedEvent',
        ];
    }

    public function onCheckPassportEvent(CheckPassportEvent $event): void
    {
        $authenticator = $event->getAuthenticator();

        if ($authenticator instanceof LoginLinkAuthenticator) {
            $this->processor->markAsConfirmed();
        }
    }

    public function onAuthenticationTokenCreatedEvent(AuthenticationTokenCreatedEvent $event): void
    {
        // make sure that this event listener only triggered for the LoginLink authenticator.
        if (false === $this->processor->isConfirmed()) {
            return;
        }

        $token = $event->getAuthenticatedToken();

        /** @var User $user */
        $user = $token->getUser();

        $invitation = $user->getInvitation();
        $invitation->setStatus(InvitationStatusEnums::CONFIRMED);

        $this->persister->update($invitation);

        // write to the history
        $event = new InvitationValidationHistoryEvent();
        $event->setEmployee($user);
        $this->eventTrigger->dispatch($event);
    }
}
