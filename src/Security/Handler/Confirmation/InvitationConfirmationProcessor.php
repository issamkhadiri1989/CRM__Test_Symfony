<?php

declare(strict_types=1);

namespace App\Security\Handler\Confirmation;

class InvitationConfirmationProcessor
{
    private bool $confirmed = false;

    public function __construct()
    {
    }

    public function process(): void
    {
    }

    public function markAsConfirmed(): void
    {
        $this->confirmed = true;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }
}
