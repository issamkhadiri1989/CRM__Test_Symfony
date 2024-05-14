<?php

declare(strict_types=1);

namespace App\Service\Timeline\Dispatcher;

use App\Event\History\AbstractHistoryEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class TimelineEventTrigger implements TimelineEventTriggerInterface
{
    public function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
    }

    public function dispatch(AbstractHistoryEvent $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
