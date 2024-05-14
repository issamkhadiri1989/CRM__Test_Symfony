<?php

declare(strict_types=1);

namespace App\Service\Timeline;

interface TimelineOrganizerInterface
{
    public function group(array $events): array;
}
