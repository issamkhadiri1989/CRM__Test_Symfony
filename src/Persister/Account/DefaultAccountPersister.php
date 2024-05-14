<?php

declare(strict_types=1);

namespace App\Persister\Account;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultAccountPersister implements AccountPersisterInterface
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }

    public function saveAccount(UserInterface $user): void
    {
        /** @var UserRepository $repository */
        $repository = $this->manager->getRepository(User::class);

        $repository->addNewEmployee($user);
    }

    public function removeAccount(UserInterface $user): void
    {
        /** @var UserRepository $repository */
        $repository = $this->manager->getRepository(User::class);

        $repository->removeEmployee($user);
    }
}
