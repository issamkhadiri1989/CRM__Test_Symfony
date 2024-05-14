<?php

declare(strict_types=1);

namespace App\Processor\Invitation;

use App\Entity\Invitation;

/**
 * this interface represents the contract facade that all invitation processors should respect.
 */
interface InvitationProcessorFacadeInterface
{
    public function process(Invitation $invitation): void;

    public function send(Invitation $invitation): void;
}
