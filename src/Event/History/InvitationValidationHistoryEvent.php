<?php

declare(strict_types=1);

namespace App\Event\History;

use App\Entity\History;
use App\Enums\HistoryTypeEnum;

class InvitationValidationHistoryEvent extends AbstractHistoryEvent
{
    public function buildHistory(): History
    {
        $history = new History();

        $history->setType(HistoryTypeEnum::INVITATION_VALIDATION)
            ->setMessage(\sprintf('"%s" validated the invitation', $this->employee->getFullName()));

        return $history;
    }
}
