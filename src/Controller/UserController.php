<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\InvitationNotConfirmedException;
use App\Form\Type\Profile\EditProfileType;
use App\Form\Type\Profile\NewAdminType;
use App\Handler\Profile\Factory\AdminAccountFactory;
use App\Handler\Profile\ProfileHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/user/new-admin', name: 'app_create_admin_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, AdminAccountFactory $factory): Response
    {
        $adminAccount = new User();

        $form = $this->createForm(NewAdminType::class, $adminAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factory->persist($adminAccount);

            $this->addFlash('success', 'A new admin user has been added.');

            return $this->redirectToRoute('app_company_list');
        }

        return $this->render('user/edit_profile.html.twig', [
            'profile' => $form,
        ]);
    }

    /**
     * @throws InvitationNotConfirmedException
     */
    #[Route('/profile', name: 'app_edit_profile')]
    public function editProfile(
        Request $request,
        ProfileHandler $handler,
    ): Response {
        /** @var User $profile */
        $profile = $this->getUser();

        $form = $this->createForm(EditProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handleConfirmedProfile($profile);

            return $this->redirectToRoute('app_edit_profile');
        }

        return $this->render('user/edit_profile.html.twig', [
            'profile' => $form,
        ]);
    }

    #[Route('/profile/view/{id}', name: 'app_view_profile')]
    #[IsGranted(attribute: 'CAN_VIEW_PROFILE', subject: 'profile')]
    public function viewProfile(User $profile): Response
    {
        return $this->render('user/profile.html.twig', [
            'profile' => $profile,
        ]);
    }

    #[Route('/profile/edit-password', name: 'edit_password_profile')]
    #[IsGranted(attribute: 'CAN_VIEW_PROFILE', subject: 'profile')]
    public function changePassword(#[CurrentUser] User $profile): Response
    {
        return $this->render('user/profile.html.twig', [
            'profile' => $profile,
        ]);
    }
}
