<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Customer;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadCustomers($manager);
    }

    private function loadCustomers(ObjectManager $manager): void
    {
        $user = new Customer();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $manager->persist($user);

        $user2 = new Customer();
        $user2->setFirstName('Mike');
        $user2->setLastName('Dupon');
        $manager->persist($user2);

        $manager->flush();
    }
}
