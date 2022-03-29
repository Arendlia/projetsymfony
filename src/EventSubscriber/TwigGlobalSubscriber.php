<?php

namespace App\EventSubscriber;

use App\Repository\CategoriesRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigGlobalSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $globalcategories;
    public function __construct(Environment $twig, CategoriesRepository $globalcategories ){
        $this->twig = $twig;
        $this->globalcategories = $globalcategories;
        
    }
    public function onControllerEvent(ControllerEvent $event)
    {
        $this->twig->addGlobal('globalcategories',$this->globalcategories->findAll());
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onControllerEvent',
            
        ];
    }
}
