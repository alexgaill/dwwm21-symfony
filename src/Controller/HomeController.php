<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $manager): Response
    {

        return $this->render('home/index.html.twig', [
            'posts' => $manager->getRepository(Post::class)->findBy(
                        [], 
                        ['createdAt' => 'DESC'],
                        5
                    )
        ]);
    }
}
