<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Event\History\ProfileConfirmedHistoryEvent;
use App\Form\Type\Profile\ChangePasswordType;
use App\Handler\Profile\ProfileHandler;
use App\Service\Timeline\Dispatcher\TimelineEventTriggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(private readonly TimelineEventTriggerInterface $eventTrigger)
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_company_list');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/verify/account', name: 'login_check')]
    public function check(): never
    {
        throw new \LogicException('This code should never be reached');
    }

    #[Route('/profile/set-password', name: 'app_set_user_password')]
    #[IsGranted(subject: 'profile', attribute: 'CAN_SET_NEW_PASSWORD')]
    public function definePassword(#[CurrentUser] User $profile, Request $request, ProfileHandler $handler): Response
    {
        $form = $this->createForm(ChangePasswordType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('newPassword')->getData();
            $handler->changePassword($data, $profile);

            $event = new ProfileConfirmedHistoryEvent();
            $event->setEmployee($profile);
            $this->eventTrigger->dispatch($event);

            return $this->redirectToRoute('app_edit_profile');
        }

        return $this->render('user/change_password.html.twig', [
            'change_password' => $form,
        ]);
    }
}
