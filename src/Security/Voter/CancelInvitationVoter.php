<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Invitation;
use App\Entity\User;
use App\Enums\InvitationStatusEnums;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CancelInvitationVoter extends Voter
{
    private const CAN_CANCEL_INVITATION = 'CAN_CANCEL_INVITATION';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::CAN_CANCEL_INVITATION === $attribute && $subject instanceof Invitation;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        // this action is not allowed for employees
        if ($this->security->isGranted('ROLE_EMPLOYEE')) {
            return false;
        }

        /** @var Invitation $invitation */
        $invitation = $subject;

        /** @var User $user */
        $user = $token->getUser();

        $company = $invitation->getCompany();

        return InvitationStatusEnums::NOT_CONFIRMED === $invitation->getStatus() && $user->isOwner($company);
    }
}
