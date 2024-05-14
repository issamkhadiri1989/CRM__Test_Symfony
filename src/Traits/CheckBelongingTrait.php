<?php

declare(strict_types=1);

namespace App\Traits;

use App\Entity\User;

trait CheckBelongingTrait
{
    private function inTheSameOrganization(User $currentUser, User $profile): bool
    {
        $currentUserOrganization = $currentUser->getCompany();

        return $profile->belongsTo($currentUserOrganization);
    }
}
