<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Entity\Invitation;

/**
 * this is the contract that should be respected by all notifiers.
 */
interface NotifierInterface
{
    public function sendInvitation(Invitation $invitation): void;
}
