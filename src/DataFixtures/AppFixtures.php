<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $admin = new User();
        $admin->setEmail('lopez.clmnt@gmail.com');
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'projectgtr'
        ));

        $manager->flush();
    }

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
}