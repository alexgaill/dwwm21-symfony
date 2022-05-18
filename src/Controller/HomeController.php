<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/search', name:'app_research', methods:['GET'], requirements:['keyword' => '\w+'])]
    public function search (Request $request, ManagerRegistry $manager, PaginatorInterface $paginator): Response
    {
        $keyword = $request->query->get('search');

        $pagination = $paginator->paginate(
            $manager->getRepository(Post::class)->search($keyword),
            $request->query->getInt('page', 1)
        );
        return $this->render('home/search.html.twig', [
            'searchResult' => $pagination
        ]);
    }
}
