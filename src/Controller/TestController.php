<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * On étend le controller par dafaut AbstractController pour utiliser ses méthodes
 * et ainsi charger une page de template
 */
final class TestController extends AbstractController{

    public function hello (): Response
    {
        return new Response("<h1>Hello </h1>");
    }

    #[Route(path:"/bye", name:"bye", methods: ["GET"])]
    public function bye (): Response
    {
        return new Response("<h1> Au revoir </h1>");
    }

    #[Route(path:"/test", name:"test", methods:["GET"])]
    public function test(): Response
    {
        return $this->render("/test/test.html.twig");
    }


}