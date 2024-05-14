<?php

declare(strict_types=1);

namespace App\Processor\Invitation;

use App\Entity\Invitation;
use App\Event\NewInvitationEvent;
use App\Persister\Account\AccountPersisterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class DefaultInvitationProcessorNotifier implements InvitationProcessorFacadeInterface
{
    public function __construct(
        private readonly AccountPersisterInterface $persister,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    public function process(Invitation $invitation): void
    {
        $user = $invitation->getEmployee();

        $this->persister->saveAccount($user);
    }

    public function send(Invitation $invitation): void
    {
        $this->process($invitation);

        // use the event dispatcher that will notify the user.
        $event = new NewInvitationEvent($invitation);
        $this->dispatcher->dispatch($event);
    }
}
