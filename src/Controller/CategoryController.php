<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(ManagerRegistry $registry): Response
    {
        /**
         * On se connecte au ManagerRegistry pour pouvoir charger et utiliser le repository des Catégories
         * la méthode getRepository permet de le charger. 
         * Elle prend en paramètre l'entité référence des éléments que l'on veut récupérer.
         * Puis on utilise la méthode findAll() pour récupérer toutes les catégories.
         */
        $categories = $registry->getRepository(Category::class)->findAll();
            
        return $this->render('category/index.html.twig', [
            'categoriesList' => $categories,
        ]);
    }
}
