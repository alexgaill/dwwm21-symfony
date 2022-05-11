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

        // On génère 10 catégories
        for ($i=0; $i < 10; $i++) { 
            $category = new Category;
            $category->setName($faker->words(3, true));
            $manager->persist($category);
            // Pour chaque catégorie on associe 10 articles
            for ($j=0; $j < 10; $j++) { 
                $post = new Post;
                $post->setTitle($faker->sentence(4))
                ->setContent($faker->paragraphs(5, true))
                ->setCreatedAt($faker->dateTime())
                // Lors d'une relation, pour associer un élément d'une table à une autre,
                // on doit passer dans le setter l'objet de l'autre table correspondant.
                // Si on passe un id au setter, symfony nous retourne une erreur
                // disant qu'il attend un objet et non un integer
                ->setCategory($category)
                ;
                $manager->persist($post);
            }
        }

        $manager->flush();
    }
}
