<?php

declare(strict_types=1);

namespace App\Event\History;

use App\Entity\Company;
use App\Entity\History;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractHistoryEvent extends Event
{
    protected string $type;

    protected User $user;

    protected User $employee;

    protected Company $company;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getEmployee(): User
    {
        return $this->employee;
    }

    public function setEmployee(User $employee): void
    {
        $this->employee = $employee;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    abstract public function buildHistory(): History;
}
