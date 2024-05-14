<?php

declare(strict_types=1);

namespace App\Persister\Account;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * This is the contract that all User persisters must respect.
 */
interface AccountPersisterInterface
{
    public function saveAccount(UserInterface $user): void;

    public function removeAccount(UserInterface $user): void;
}
