<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setEmail('jhennebo@gmail.com');
        $admin->setLastname('hennebo');
        $admin->setFirstname('julien');
        $admin->setAddress('6 bd liberté');
        $admin->setCity('Berck');
        $admin->setCountry('France');
        $admin->setPhone('0656565656');
        $admin->setZipcode('62600');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin')
        );
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');

        for($usr = 1; $usr <= 5; $usr++){
            $user = new Users();
            $user->setEmail($faker->email);
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setAddress($faker->streetAddress);
            $user->setCity($faker->city);
            $user->setCountry($faker->country);
            $user->setPhone($faker->phoneNumber);
            $user->setZipcode(str_replace(' ', '', $faker->postcode));
            //remplacer les espaces par rien str replace
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, 'secret')
            );


            $manager->persist($user);

        }


        $manager->flush();
    }
}
