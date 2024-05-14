<?php

declare(strict_types=1);

namespace App\Enums;

enum HistoryTypeEnum: string
{
    case NEW_INVITATION = 'Sending invitation';
    case CONFIRMING_EMPLOYEE_PROFILE = 'Confirming an employee profile';
    case INVITATION_VALIDATION = 'Invitation validation';
    case CANCELLATION_INVITATION = 'Cancellation of invitation';
}
