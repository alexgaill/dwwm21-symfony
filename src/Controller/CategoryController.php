<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        $categories = [
            [
                "name" => "catégorie n°1"
            ],
            [
                "name" => "catégorie n°2"
            ],
            [
                "name" => "catégorie n°3"
            ],
            [
                "name" => "catégorie n°4"
            ]
            ];
            
        return $this->render('category/index.html.twig', [
            'categoriesList' => $categories,
        ]);
    }
}
