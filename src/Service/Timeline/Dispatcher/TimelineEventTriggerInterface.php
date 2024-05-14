<?php

declare(strict_types=1);

namespace App\Service\Timeline\Dispatcher;

use App\Event\History\AbstractHistoryEvent;

interface TimelineEventTriggerInterface
{
    public function dispatch(AbstractHistoryEvent $event): void;
}
