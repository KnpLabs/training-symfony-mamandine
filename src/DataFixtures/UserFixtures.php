<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@mail.com');
        $user->setRoles(['ROLE_ADMIN']);

        $plainPassword = 'admin';

        $hash = $this->passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword($hash);

        $manager->persist($user);
        $manager->flush();
    }
}
