<?php

declare(strict_types=1);

namespace App\Handler\Company;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class DefaultCompanyHandler implements CompanyHandlerInterface
{
    public function __construct(private readonly EntityManagerInterface $manager, private readonly Security $security)
    {
    }

    public function handlePersistInstance(Company $company): void
    {
        /** @var User $companyManager */
        $companyManager = $this->security->getUser();

        // affect the company to the current admin
        $company->setManager($companyManager);

        $this->manager
            ->getRepository(Company::class)
            ->saveNewInstance($company);
    }

    public function removeInstance(Company $company): void
    {
        $this->manager
            ->getRepository(Company::class)
            ->removeCompany($company);
    }
}
