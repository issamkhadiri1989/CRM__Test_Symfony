<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Invitation;
use App\Entity\User;
use App\Event\History\CancelledInvitationHistoryEvent;
use App\Event\History\NewInvitationHistoryEvent;
use App\Form\Type\InvitationType;
use App\Persister\Invitation\InvitationPersisterInterface;
use App\Processor\Invitation\InvitationProcessorFacadeInterface;
use App\Service\Timeline\Dispatcher\TimelineEventTriggerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class InvitationController extends AbstractController
{
    public function __construct(private readonly TimelineEventTriggerInterface $dispatcher)
    {
    }

    #[Route('/invitation/{identifier}', name: 'app_invitation')]
    public function index(
        #[MapEntity(mapping: ['identifier' => 'id'])] Company $company,
        Request $request,
        InvitationProcessorFacadeInterface $processor,
        InvitationPersisterInterface $invitationPersister,
        #[CurrentUser] User $user,
    ): Response {
        $form = $this->createForm(InvitationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Invitation $invitation */
            $invitation = $form->getData();
            $invitation->setCompany($company);

            // persist the new invitation
            $invitationPersister->persist($invitation);

            // sent the invitation to the end user
            $processor->send($invitation);

            // <editor-fold desc="add new history item">
            $event = new NewInvitationHistoryEvent();
            $event->setCompany($company);
            $event->setEmployee($invitation->getEmployee());
            $event->setUser($user);

            $this->dispatcher->dispatch($event);
            // </editor-fold>

            $this->addFlash(
                'success',
                \sprintf('An invitation has been sent to %s', $invitation->getEmployee()->getEmail())
            );

            return $this->redirectToRoute('app_list_invitations');
        }

        return $this->render('invitation/index.html.twig', [
            'invitation' => $form,
            'company' => $company,
        ]);
    }

    #[Route('/invitation/list', name: 'app_list_invitations', priority: 2)]
    public function viewInvitations(#[CurrentUser] User $user): Response
    {
        $invitation = $user->getInvitations();

        return $this->render('invitation/list.html.twig', [
            'invitations' => $invitation,
        ]);
    }

    #[Route('/invitation/remove/{id}', name: 'app_remove_invitation', priority: 2)]
    #[IsGranted(attribute: 'CAN_CANCEL_INVITATION', subject: 'invitation')]
    public function removeInvitation(
        Invitation $invitation, InvitationPersisterInterface $persister,
        #[CurrentUser] User $user,
    ): Response {
        $persister->remove($invitation);

        $event = new CancelledInvitationHistoryEvent();
        $event->setUser($user);

        $this->dispatcher->dispatch($event);

        return $this->redirectToRoute('app_list_invitations');
    }
}
