<?php

declare(strict_types=1);

namespace App\Handler\Profile\Factory;

use App\Entity\User;

class EmployeeAccountFactory extends AbstractAccountFactory
{
    public function buildAccount(User $input, #[\SensitiveParameter] string $plainPassword): User
    {
        return $input->setUsername($input->getEmail())
            ->setPassword($plainPassword)
            ->setConfirmed(false)
            ->setRoles(['ROLE_EMPLOYEE']);
    }
}
