<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:"/category")]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category')]
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

    #[Route(path:'/{id}', name:'app_single_category', methods:["GET"], requirements: ['id' => "\d+"])]
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

    #[Route(path:"/add", name:"app_add_category", methods:["GET", "POST"])]
    public function add (Request $request, ManagerRegistry $manager): Response
    {
        $category = new Category;

        // On génère un formulaire grâce au composant form de symfony.
        // On appelle la méthode createFormBuilder 
        // puis la méthode add pour chaque champ à ajouter.

        $form = $this->createFormBuilder($category)
                ->add('name', TextType::class, [
                    'label' => "Nom de la catégorie:",
                    'attr' => [
                        'placeholder' => 'Nouvelle catégorie'
                    ]
                ])
                ->add('submit', SubmitType::class, [
                    'label' => "Envoyer"
                ])
                ->getForm();
        // On associe les informations récupérées par la class Request à notre formulaire
        $form->handleRequest($request);
        
        // Si le formulaire est soumis et que les données reçues correspondent à ce qui est attendu dans l'entité
        if ($form->isSubmitted() && $form->isValid()) {
            // On charge l'ObjectManager et on enregistre les données en BDD.
            $objectManager = $manager->getManager();
            $objectManager->persist($category);
            $objectManager->flush();
            
            $this->addFlash('success', "Nouvelle catégorie ajoutée");
            return $this->redirectToRoute('app_single_category', ['id' => $category->getId()]);
        }
        
        // Lorsqu'on passe un formulaire à notre vue, 
        // on doit utiliser la méthode renderForm et non render pour le template.
        return $this->renderForm('category/add.html.twig', [
            'categoryForm' => $form
        ]);
    }

    #[Route(path:'/{id}/update', name:'app_update_category', methods:['GET', 'POST'], requirements:['id' => "\d+"])]
    public function update (Category $category, Request $request, ManagerRegistry $manager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->getRepository(Category::class)->add($category, true);
            $this->addFlash('success', "La catégorie ".$category->getName()." a été modifiée avec succés ");
            
            return $this->redirectToRoute('app_single_category', ['id' => $category->getId()]);
        }

        return $this->renderForm('category/update.html.twig', [
            'categoryForm' => $form,
            'category' => $category
        ]);
    }

    #[Route('/{id}/delete', name:'app_delete_category', requirements: ['id' => "\d+"], methods: ['GET'])]
    public function delete(Category $category, ManagerRegistry $manager): Response
    {
        $manager->getRepository(Category::class)->remove($category, true);
        $this->addFlash('success', "La catégorie ".$category->getName()." a été supprimée avec succés ");

        return $this->redirectToRoute('app_category');
    }
}
