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

    #[Route(path:'/category/{id}', name:'app_single_category', methods:["GET"], requirements: ['id' => "\d+"])]
    public function single(int $id, ManagerRegistry $registry): Response
    {
        $category = $registry->getRepository(Category::class)->find($id);
        
        // Si l'id correspond à une catégorie présente en BDD, on affiche le template
        if ($category) {
            return $this->render('category/single.html.twig', [
                'category' => $category
            ]);
        } else {
            // Redirige l'utilisateur sur la page affichant toutes les catégories
            return $this->redirectToRoute('app_category');
        }
    }
}
