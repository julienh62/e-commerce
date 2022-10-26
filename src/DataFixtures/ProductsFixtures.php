<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProductsFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {

    }

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        for($prod = 1; $prod <= 5; $prod++){
            $product = new products();
            $product->setName($faker->name);
            $product->setDescription($faker->text(15));
            $product->setSlug($this->slugger->slug($product->getName())->lower());
            $product->setPrice($faker->numberBetween(900, 150000));
            $product->setStock($faker->numberBetween(0, 10));

            //on va chercher une reference de categorie (de façon aleatoire)
            $category = $this->getReference('cat-'. rand(1, 8));
            $product->setCategories($category);

            $manager->persist($product);


        }


        $manager->flush();
    }
}