<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CanViewCompanyVoter extends Voter
{
    private const CAN_VIEW_COMPANY = 'CAN_VIEW_COMPANY';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::CAN_VIEW_COMPANY === $attribute && $subject instanceof Company;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        // for admins, it is always true to view other companies' details
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var Company $company */
        $company = $subject;

        /** @var User $user */
        $user = $token->getUser();

        if ($this->security->isGranted('ROLE_EMPLOYEE') && $user->isConfirmed() && $user->belongsTo($company)) {
            return true;
        }

        return false;
    }
}
