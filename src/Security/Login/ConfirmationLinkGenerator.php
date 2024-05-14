<?php

declare(strict_types=1);

namespace App\Security\Login;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class ConfirmationLinkGenerator
{
    public function __construct(private readonly LoginLinkHandlerInterface $linkHandler)
    {
    }

    public function generateLoginLink(UserInterface $user): string
    {
        $loginLink = $this->linkHandler->createLoginLink($user);

        return $loginLink->getUrl();
    }
}
