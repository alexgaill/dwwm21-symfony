<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(ManagerRegistry $manager): Response
    {
        return $this->render('post/index.html.twig', [
            'postsList' => $manager->getRepository(Post::class)->findAll()
        ]);
    }

    #[Route('/{id}', name:'app_single_post', 
    methods: ["GET"], requirements: ['id' => "\d+"])]
    public function single (int $id, ManagerRegistry $manager): Response
    {
        return $this->render('post/single.html.twig', [
            'post' => $manager->getRepository(Post::class)->find($id)
        ]);
    }

    #[Route('/add', name:'app_add_post', methods:['GET', 'POST'])]
    public function add(Request $request, ManagerRegistry $manager): Response
    {
        $post = new Post;

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new DateTime());
            $manager->getRepository(Post::class)->add($post, true);

            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()]);
        }

        return $this->renderForm('post/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route("/{id}/update", name:'app_update_post', methods:['GET', 'POST'], requirements:['id' => '\d+'])]
    public function update(Post $post, Request $request, ManagerRegistry $manager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->getRepository(Post::class)->add($post, true);

            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()]);
        }

        return $this->renderForm('post/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route("/{id}/delete", name:'app_delete_post', methods:['GET'], requirements:['id' => '\d+'])]
    public function delete(Post $post, ManagerRegistry $manager): Response
    {
        $om = $manager->getManager();
        $om->remove($post);
        $om->flush();

        return $this->redirectToRoute('app_post');
    }
}
