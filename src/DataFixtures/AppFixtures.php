<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr-FR');

        for ($i=0; $i < 10; $i++) { 
            $category = new Category;
            $category->setName($faker->words(3, true));
            $manager->persist($category);
        }

        for ($j=0; $j < 100; $j++) { 
            $post = new Post;
            $post->setTitle($faker->sentence(4))
                ->setContent($faker->paragraphs(5, true))
                ->setCreatedAt($faker->dateTime());
            $manager->persist($post);
        }

        $manager->flush();
    }
}
