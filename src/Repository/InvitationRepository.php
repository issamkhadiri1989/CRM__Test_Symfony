<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Invitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invitation>
 */
class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    public function saveNewInvitation(Invitation $invitation)
    {
        $this->doSaveInvitation($invitation, true);
    }

    public function saveExistingInstance(Invitation $invitation): void
    {
        $this->doSaveInvitation($invitation);
    }

    public function removeInvitation(Invitation $invitation): void
    {
        $manager = $this->getEntityManager();

        $manager->remove($invitation->getEmployee());
        $manager->remove($invitation);
        $manager->flush();
    }

    private function doSaveInvitation(Invitation $invitation, bool $mustPersist = false): void
    {
        $manager = $this->getEntityManager();

        if (true === $mustPersist) {
            $manager->persist($invitation);
        }

        $manager->flush();
    }
}
