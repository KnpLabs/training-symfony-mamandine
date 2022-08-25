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

        // Create new users
        $rawUsers = [
            [ "Torrence", "Hustings", "thustings0@shop-pro.jp", "Finland" ],
            [ "Joane", "Strowan", "jstrowan1@china.com.cn", "Poland" ],
            [ "Vonnie", "Searchwell", "vsearchwell2@cloudflare.com", "Kenya" ],
            [ "Gerry", "Theobalds", "gtheobalds3@live.com", "China" ],
            [ "Constantina", "Pastor", "cpastor4@usatoday.com", "France" ],
            [ "Eleonora", "Rappport", "erappport5@google.de", "France" ],
            [ "Norry", "Binham", "nbinham6@boston.com", "Dominican Republic" ],
            [ "Lily", "Rickwood", "lrickwood7@who.int", "China" ],
            [ "Gasparo", "Palin", "gpalin8@macromedia.com", "Netherlands" ],
            [ "Susana", "Caudrey", "scaudrey9@mac.com", "Greece" ]
        ];

        foreach ($rawUsers as $rawUser) {
            $user = new User();
            $user->setFirstname($rawUser[0]);
            $user->setLastname($rawUser[1]);
            $user->setEmail($rawUser[2]);
            $user->setNationality($rawUser[3]);
            $user->setRoles(['ROLE_USER']);

            $plainPassword = 'user';
            $hash = $this->encoder->encodePassword($user, $plainPassword);
            $user->setPassword($hash);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
