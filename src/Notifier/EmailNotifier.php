<?php

declare(strict_types=1);

namespace App\Notifier;

use App\Entity\Invitation;
use App\Security\Login\ConfirmationLinkGenerator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class EmailNotifier implements NotifierInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly ConfirmationLinkGenerator $confirmationLinkGenerator,
    ) {
    }

    public function sendInvitation(Invitation $invitation): void
    {
        $message = $this->doBuildEmail($invitation);

        // add the login link here

        $this->mailer->send($message);
    }

    private function doBuildEmail(Invitation $invitation): Email
    {
        $recipient = $invitation->getEmployee();
        $company = $invitation->getCompany();

        $confirmationLink = $this->confirmationLinkGenerator->generateLoginLink($recipient);

        return (new TemplatedEmail())/* ->from('khadiri.issam@gmail.com') */
        ->to($recipient->getUserIdentifier())
            ->htmlTemplate('email/validation.html.twig')
            ->context(['company' => $company, 'employee' => $recipient, 'confirmation_link' => $confirmationLink]);
    }
}
