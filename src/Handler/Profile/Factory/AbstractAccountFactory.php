<?php

declare(strict_types=1);

namespace App\Handler\Profile\Factory;

use App\Entity\User;
use App\Persister\Account\AccountPersisterInterface;

abstract class AbstractAccountFactory
{
    public function __construct(protected readonly AccountPersisterInterface $persister)
    {
    }

    abstract public function buildAccount(User $input, #[\SensitiveParameter] string $plainPassword): User;

    public function persist(User $user): void
    {
        $account = $this->buildAccount($user, $user->getPassword());
        $this->persister->saveAccount($account);
    }
}
