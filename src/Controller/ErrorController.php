<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController {

    public function show ($exception): Response
    {
        return $this->render("error/error.html.twig", [
            'content' => 'content par défaut',
            'exception' => [
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
                ]
        ]);
    }
    
}