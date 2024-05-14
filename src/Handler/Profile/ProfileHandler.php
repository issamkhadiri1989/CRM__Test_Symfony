<?php

declare(strict_types=1);

namespace App\Handler\Profile;

use App\Entity\User;
use App\Exception\InvitationNotConfirmedException;
use App\Handler\Profile\Password\PasswordGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileHandler
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly PasswordGeneratorInterface $generator,
    ) {
    }

    public function handleConfirmedProfile(UserInterface $account): void
    {
        /** @var User $profile */
        $profile = $account;

        if ($profile->getInvitation()->isConfirmed()) {
            $profile->setConfirmed(true);
            $this->manager->flush();

            return;
        }

        throw new InvitationNotConfirmedException('Cannot edit profile because the invitation not confirmed');
    }

    public function changePassword(
        #[\SensitiveParameter] string $password,
        PasswordAuthenticatedUserInterface $user,
    ): void {
        if (!$user instanceof User) {
            return;
        }

        $hashedPassword = $this->generator->generatePassword($password, $user);

        $user->setPassword($hashedPassword);
        $this->manager->flush();
    }
}
