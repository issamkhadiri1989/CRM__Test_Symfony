<?php

declare(strict_types=1);

namespace App\Handler\Profile\Factory;

use App\Entity\User;
use App\Handler\Profile\Password\PasswordGeneratorInterface;
use App\Persister\Account\AccountPersisterInterface;

class AdminAccountFactory extends AbstractAccountFactory
{
    public function __construct(
        AccountPersisterInterface $persister,
        private readonly PasswordGeneratorInterface $passwordGenerator
    ) {
        parent::__construct($persister);
    }

    public function buildAccount(User $input, #[\SensitiveParameter] string $plainPassword): User
    {
        $password = $this->passwordGenerator->generatePassword($plainPassword, $input);

        return $input->setPassword($password)
            ->setUsername($input->getEmail())
            ->setRoles(['ROLE_ADMIN'])
            ->setConfirmed(true);
    }
}
