<?php

declare(strict_types=1);

namespace App\Handler\Profile\Password;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class DefaultPasswordManager implements PasswordGeneratorInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function generatePassword(
        #[\SensitiveParameter] string $plainPassword,
        PasswordAuthenticatedUserInterface $user,
    ): string {
        /** @var User $account */
        $account = $user;

        $hashedPassword = $this->hasher->hashPassword($account, $plainPassword);

        $account->setPassword($hashedPassword);

        return $hashedPassword;
    }
}
