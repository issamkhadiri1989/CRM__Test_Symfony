<?php

declare(strict_types=1);

namespace App\Handler\Company;

use App\Entity\Company;

/**
 * This interface is the contract the all Company handlers should respect when handling a Company instance.
 */
interface CompanyHandlerInterface
{
    public function handlePersistInstance(Company $company): void;

    public function removeInstance(Company $company): void;
}
