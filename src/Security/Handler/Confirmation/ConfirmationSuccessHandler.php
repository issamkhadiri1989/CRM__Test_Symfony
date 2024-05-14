<?php

declare(strict_types=1);

namespace App\Security\Handler\Confirmation;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

final class ConfirmationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private readonly UrlGeneratorInterface $generator)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();

        $targetPage = $this->generator->generate(
            (empty($user->getPassword())) ? 'app_set_user_password' : 'app_edit_profile',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new RedirectResponse($targetPage);
    }
}
