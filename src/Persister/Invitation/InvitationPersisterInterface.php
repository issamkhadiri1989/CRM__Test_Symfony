<?php

declare(strict_types=1);

namespace App\Persister\Invitation;

use App\Entity\Invitation;

/**
 * this interface represents the contract that all persisters should respect to persist an invitation.
 */
interface InvitationPersisterInterface
{
    public function persist(Invitation $invitation): void;

    public function update(Invitation $invitation): void;

    public function remove(Invitation $invitation): void;
}
