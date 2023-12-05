<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $this->loadUsers($manager);
        $this->loadProducts($manager,$faker);
    }

    public function loadUsers(ObjectManager $manager): void
    {
        //ADMIN
        $admin = new User();
        $admin->setEmail("admin@mail.dev");
        $admin->setPassword($this->hasher->hashPassword($admin,"password"));
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

//        //PREPARATEURS
//        for($i=1; $i<=5;$i++){
//            $preparateur = new User();
//            $preparateur->setEmail("operator-$i@mail.dev");
//            $preparateur->setPassword($this->hasher->hashPassword($preparateur,"password"));
//            $preparateur->setRoles(["ROLE_PREPARATEUR"]);
//            $manager->persist($preparateur);
//        }

        //CLIENTS
        for($i=1; $i<=10;$i++){
            $client = new User();
            $client->setEmail("client-$i@mail.dev");
            $client->setPassword($this->hasher->hashPassword($client,"password"));
            $client->setRoles(["ROLE_CLIENT"]);
            $manager->persist($client);
        }

        $manager->flush();
    }

    private function loadProducts(ObjectManager $manager, Generator $faker): void
    {
        for($i=0; $i<5;$i++){
            $product = new Produit();
            $product->setNom(ucfirst($faker->word()));
            $product->setPrix($faker->randomNumber(3, true));
            $product->setStock($faker->numberBetween(0, 10));

            $manager->persist($product);
        }
        $manager->flush();
    }
}
