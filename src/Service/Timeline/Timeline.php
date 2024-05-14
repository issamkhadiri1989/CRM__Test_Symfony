<?php

declare(strict_types=1);

namespace App\Service\Timeline;

use App\Entity\History;
use App\Repository\HistoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class Timeline
{
    public function __construct(private readonly EntityManagerInterface $manager, private readonly TimelineOrganizerInterface $organizer)
    {
    }

    public function renderTimeline(): array
    {
        /** @var HistoryRepository $repository */
        $repository = $this->manager->getRepository(History::class);

        $timeline = $repository->getTimeline();

        return $this->organizer->group($timeline);
    }
}
