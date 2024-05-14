<?php

declare(strict_types=1);

namespace App\Event\History;

use App\Entity\History;
use App\Enums\HistoryTypeEnum;

class ProfileConfirmedHistoryEvent extends AbstractHistoryEvent
{
    public function buildHistory(): History
    {
        $history = new History();

        $history
            ->setMessage(\sprintf('"%s" has confirmed his profile', $this->getEmployee()->getFullName()))
            ->setType(HistoryTypeEnum::CONFIRMING_EMPLOYEE_PROFILE);

        return $history;
    }
}
