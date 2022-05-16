<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(ManagerRegistry $manager, Request $request, PaginatorInterface $paginator): Response
    {
        // $offset = max(0, $request->query->getInt('offset', 0));
        // $paginator = $manager->getRepository(Post::class)->postPaginator($offset);

        $pagination = $paginator->paginate(
            $manager->getRepository(Post::class)->findAll(),
            $request->query->getInt('page', 1)
        );

        return $this->render('post/index.html.twig', [
            // 'postsList' => $manager->getRepository(Post::class)->findAll(),
            // 'postsList' => $paginator,
            // 'previous' => $offset - PostRepository::PAGINATOR_PER_PAGE,
            // 'next' => min(count($paginator), $offset + PostRepository::PAGINATOR_PER_PAGE),
            // 'postQty' => PostRepository::PAGINATOR_PER_PAGE
            'postsList' => $pagination
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
            $picture = $form->get('picture')->getData();
            if ($picture) {
                $pictureName = md5(uniqid()). "." .$picture->guessExtension();
                
                $picture->move($this->getParameter('upload_dir'), $pictureName);
            }
                
            $post->setCreatedAt(new DateTime())
                // Condition ternaire => Si $pictureName a une valeur on utilise la valeur sinon on retourne null
                ->setPicture($picture ? $pictureName : null );
                // ->setPicture($pictureName ?? null );
            
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
        /**
         * Si l'article possède déjà une image, symfony va vouloir la charger en temps qu'image
         * pour la passer en valeur par défaut à l'input File.
         * On doit donc générer l'image à partir de la chaine de caractère présente en BDD.
         * 
         * Pour permettre de générer ce fichier, le setPicture doit accepter le type File pour le paramètre
         * 
         * La deuxième solution est de passer à l'input file l'option mapped à false
         */ 
        if ($post->getPicture()) {
            // On garde en mémoire le nom de l'ancienne image pour pouvoir la redonner ou la supprimer
            $oldPicture = $post->getPicture();
            $post->setPicture(
                new File($this->getParameter('upload_dir'). '/' . $post->getPicture())
            );
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();
            // Si une image est passée dans l'input File
            if ($picture) {
                // On génère le nom de la nouvelle image
                $pictureName = md5(uniqid()). '.' .$picture->guessExtension();
                // On stocke la nouvelle image
                $picture->move($this->getParameter('upload_dir'), $pictureName);
                // On enregistre le nouveau nom
                $post->setPicture($pictureName);
                // Si on a une ancienne image, on la supprime de notre dossier upload pour éviter de surcharger notre serveur
                // avec des images inutiles.
                if (isset($oldPicture)) {
                    unlink($this->getParameter('upload_dir'). '/' . $oldPicture);
                }
            } else {
                // Si aucune image n'a été passé à l'input File,
                // on vérifie si l'article avait déjà une image.
                // Si c'est le cas, on lui réattribut cette ancienne image
                if (isset($oldPicture)) {
                    $post->setPicture($oldPicture);
                }
            }
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
