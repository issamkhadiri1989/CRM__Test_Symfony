<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Traits\CheckBelongingTrait;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ViewOtherProfileVoter extends Voter
{
    use CheckBelongingTrait;
    private const CAN_VIEW_PROFILE = 'CAN_VIEW_PROFILE';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::CAN_VIEW_PROFILE === $attribute && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        // make sure that the admin can always view the profile's view
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var User $user */
        $user = $token->getUser();

        return $this->inTheSameOrganization($user, $subject);
    }
}
