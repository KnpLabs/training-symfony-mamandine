<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@mail.com');
        $user->setRoles(['ROLE_ADMIN']);

        $plainPassword = 'admin';

        $hash = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($hash);

        $manager->persist($user);
        $manager->flush();
    }
}
