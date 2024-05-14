<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<History>
 */
class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function getTimeline(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.historyDatetime', 'desc')
            ->getQuery()
            ->getResult();
    }
}
