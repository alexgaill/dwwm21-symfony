<?php
namespace App\EventSubscriber;

use App\Entity\Category;
use Twig\Environment;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class MenuEventSubscriber implements EventSubscriberInterface {

    // private $twig;

    // public function __construct(Environment $twig)
    // {
    //     $this->twig = $twig;
    // }

    public function __construct(
        private Environment $twig, 
        private ManagerRegistry $manager
    ){}

    public function onControllerEvent(ControllerEvent $event)
    {
        $this->twig->addGlobal('menuCategory', $this->manager->getRepository(Category::class)->findAll());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onControllerEvent',
        ];
    }
}