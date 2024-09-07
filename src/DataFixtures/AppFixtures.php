<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;



class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        //Creation de deux ROle User et Admin avec Un Admin et 10 USer
        $adminRole = new Role();
        $userRole = new Role();
        $adminRole->setLabel('ROLE_ADMIN');
        $userRole->setLabel('ROLE_User');
        $manager->persist($adminRole);
        $manager->persist($userRole);

        $user = new User();
        $user->setUsername('admin ');
        $user->setPassword('your_password ');
        $user->setNom('admin name  ');
        $user->setPrénom('adminPrénom');
        $user->addRole($adminRole);
        $manager->persist($user);
        for ($i = 1; $i < 11; $i++) {
            $user = new User();
            $user->setUsername('User ' . $i);
            $user->setPassword('your_password ' . $i);
            $user->setNom('User name  ' . $i);
            $user->setPrénom('UserPrénom' . $i);
            $user->addRole($userRole);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
