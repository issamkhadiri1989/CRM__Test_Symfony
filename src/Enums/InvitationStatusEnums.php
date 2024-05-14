<?php

declare(strict_types=1);

namespace App\Enums;

enum InvitationStatusEnums: string
{
    case NOT_CONFIRMED = 'not_confirmed';

    case CONFIRMED = 'confirmed';
}
