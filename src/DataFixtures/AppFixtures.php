<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(1, [
            'username' => 'admin@crm.com',
            'roles' => ['ROLE_ADMIN'],
            'password' => '1234',
        ]);

        UserFactory::createMany(1, [
            'username' => 'admin-02@crm.com',
            'roles' => ['ROLE_ADMIN'],
            'password' => '1234',
        ]);

        $manager->flush();
    }
}
