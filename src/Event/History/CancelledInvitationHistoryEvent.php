<?php

declare(strict_types=1);

namespace App\Event\History;

use App\Entity\History;
use App\Enums\HistoryTypeEnum;

class CancelledInvitationHistoryEvent extends AbstractHistoryEvent
{
    public function buildHistory(): History
    {
        $trigger = $this->getUser();

        $history = new History();
        $history->setType(HistoryTypeEnum::CANCELLATION_INVITATION);
        $history->setMessage(\sprintf('"%s" has canceled the invitation', $trigger->getFullName()));

        return $history;
    }
}
