<?php

declare(strict_types=1);

namespace App\Handler\Profile\Password;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * this interface is the contract that every password handler should respect.
 */
interface PasswordGeneratorInterface
{
    public function generatePassword(
        #[\SensitiveParameter] string $plainPassword,
        PasswordAuthenticatedUserInterface $user
    ): string;
}
