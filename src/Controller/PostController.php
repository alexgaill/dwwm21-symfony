<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
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
    public function index(ManagerRegistry $manager, Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $manager->getRepository(Post::class)->postPaginator($offset);

        return $this->render('post/index.html.twig', [
            'postsList' => $manager->getRepository(Post::class)->findAll(),
            'postsList' => $paginator,
            'previous' => $offset - PostRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + PostRepository::PAGINATOR_PER_PAGE),
            'postQty' => PostRepository::PAGINATOR_PER_PAGE
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

            $this->addFlash('success', "L'article ".$post->getTitle()." a été enregistré avec succés ");
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

            $this->addFlash('success', "L'article ".$post->getTitle()." a été modifié avec succés ");
            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()]);
        }

        return $this->renderForm('post/update.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }

    #[Route("/{id}/delete", name:'app_delete_post', methods:['GET'], requirements:['id' => '\d+'])]
    public function delete(Post $post, ManagerRegistry $manager): Response
    {
        $om = $manager->getManager();
        $om->remove($post);
        $om->flush();

        $this->addFlash('success', "L'article ".$post->getTitle()." a été supprimé avec succés ");
        return $this->redirectToRoute('app_post');
    }
}
