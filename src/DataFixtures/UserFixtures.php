<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;


class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $clement = new User();
        $clement->setEmail('lopez.clmnt@gmail.com');
        $clement->setRoles(["ROLE_ADMIN"]);
        $clement->setPassword($this->passwordEncoder->encodePassword(
            $clement,
            'projectgtr'
        ));
        $manager->persist($clement);
        $manager->flush();
    }

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
}