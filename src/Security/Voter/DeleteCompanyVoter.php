<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class DeleteCompanyVoter extends Voter
{
    private const CAN_DELETE_COMPANY = 'CAN_DELETE_COMPANY';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::CAN_DELETE_COMPANY === $attribute && $subject instanceof Company;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var Company $company */
        $company = $subject;

        /** @var User $user */
        $user = $token->getUser();

        return $this->security->isGranted('ROLE_ADMIN')
            && $user->isOwner($company)
            && 0 === $company->getEmployees()->count();
    }
}
