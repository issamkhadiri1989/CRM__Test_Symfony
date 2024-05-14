<?php

declare(strict_types=1);

namespace App\Event\History;

use App\Entity\History;
use App\Enums\HistoryTypeEnum;

class NewInvitationHistoryEvent extends AbstractHistoryEvent
{
    public function buildHistory(): History
    {
        $history = new History();

        $history->setType(HistoryTypeEnum::NEW_INVITATION)
            ->setMessage(\sprintf(
                'Admin "%s" invited employee "%s" to join company "%s"',
                $this->getUser()->getFullName(),
                $this->getEmployee()->getFullName(),
                $this->getCompany()->getName()
            ));

        return $history;
    }
}
