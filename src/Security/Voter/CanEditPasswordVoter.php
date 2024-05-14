<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Enums\InvitationStatusEnums;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CanEditPasswordVoter extends Voter
{
    private const CAN_SET_NEW_PASSWORD = 'CAN_SET_NEW_PASSWORD';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::CAN_SET_NEW_PASSWORD === $attribute && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $account */
        $account = $token->getUser();

        return InvitationStatusEnums::CONFIRMED === $account->getInvitation()->getStatus()
            && empty($account->getPassword())
            && false === $account->isConfirmed();
    }
}
