<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Invitation;
use Symfony\Contracts\EventDispatcher\Event;

class NewInvitationEvent extends Event
{
    public function __construct(private readonly Invitation $invitation)
    {
    }

    public function getInvitation(): Invitation
    {
        return $this->invitation;
    }
}
