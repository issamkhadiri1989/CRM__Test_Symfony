<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function saveNewInstance(Company $company): void
    {
        $this->doSaveInstance($company, true);
    }

    public function updateInstance(Company $company): void
    {
        $this->doSaveInstance($company);
    }

    public function removeCompany(Company $company)
    {
        $this->getEntityManager()->remove($company);

        $this->doSaveInstance($company);
    }

    private function doSaveInstance(Company $company, bool $mustPersist = false): void
    {
        $manager = $this->getEntityManager();

        if (true == $mustPersist) {
            $manager->persist($company);
        }

        $manager->flush();
    }
}
