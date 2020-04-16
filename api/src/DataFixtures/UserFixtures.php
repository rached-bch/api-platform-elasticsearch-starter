<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'hellouser10$'));
        $user->setEmail('adresse1@domainevirtuel.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $user2 = new User();
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, 'hellouser10$'));
        $user2->setFirstName('Mike');
        $user2->setLastName('Dupon');
        $user2->setEmail('adresse2@domainevirtuel.com');
        $user2->setRoles(['ROLE_ADMIN']);
        $manager->persist($user2);

        $manager->flush();
    }
}
