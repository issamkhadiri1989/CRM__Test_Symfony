<?php

declare(strict_types=1);

namespace App\Persister\Invitation;

use App\Entity\Invitation;
use App\Entity\User;
use App\Enums\InvitationStatusEnums;
use App\Exception\UserNotLoggedInException;
use App\Handler\Profile\Factory\EmployeeAccountFactory;
use App\Persister\Account\AccountPersisterInterface;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class DefaultInvitationPersister implements InvitationPersisterInterface
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly AccountPersisterInterface $persister,
        private readonly Security $security,
        private readonly EmployeeAccountFactory $factory,
    ) {
    }

    public function persist(Invitation $invitation): void
    {
        if (!$this->security->getUser()) {
            throw new UserNotLoggedInException();
        }

        /** @var User $user */
        $admin = $this->security->getUser();

        $this->doCreateNewEmployeeRelatedToInvitation($invitation);
        $this->doCompleteInvitationBeforePersist($invitation, $admin);

        /** @var InvitationRepository $repository */
        $repository = $this->manager->getRepository(Invitation::class);
        $repository->saveNewInvitation($invitation);
    }

    public function remove(Invitation $invitation): void
    {
        /** @var InvitationRepository $repository */
        $repository = $this->manager->getRepository(Invitation::class);

        $attachedEmployee = $invitation->getEmployee();

        $this->persister->removeAccount($attachedEmployee);

        $repository->removeInvitation($invitation);
    }

    public function update(Invitation $invitation): void
    {
        /** @var InvitationRepository $repository */
        $repository = $this->manager->getRepository(Invitation::class);

        $repository->saveExistingInstance($invitation);
    }

    private function doCreateNewEmployeeRelatedToInvitation(Invitation $invitation): void
    {
        $employee = $invitation->getEmployee()->setPassword('');
        $employee->setCompany($invitation->getCompany());

        $this->factory->persist($employee);
    }

    private function doCompleteInvitationBeforePersist(Invitation $invitation, User $user)
    {
        $invitation
            ->setStatus(InvitationStatusEnums::NOT_CONFIRMED)
            ->setSentAt(new \DateTimeImmutable())
            ->setAuthor($user);
    }
}
