<?php

declare(strict_types=1);

namespace App\Service\Timeline;

use App\Entity\History;

class TimelineOrganizer implements TimelineOrganizerInterface
{
    /**
     * @param History[] $events
     */
    public function group(array $events): array
    {
        $output = [];

        foreach ($events as $event) {
            $day = $event->getHistoryDatetime()->format('d-m-Y');
            $output[$day][] = [
                'type' => $event->getType(),
                'message' => $event->getMessage(),
                'time' => $event->getHistoryDatetime()->format('H:i'),
            ];
        }

        return $output;
    }
}
