<?php

namespace App\EventListener;

use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonnesEvent;
use Psr\Log\LoggerInterface;

class PersonneListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }
    public function onPersonneAdd(AddPersonneEvent $event)
    {
        $this->logger->debug("je suis entrain d'écouter l'évenement personne.add et la personne ajouté est " . $event->getPersonne()->getName());
    }
    public function onPersonneList(ListAllPersonnesEvent $event)
    {
        $this->logger->debug("le nombre de personne dans la bdd est  " . $event->getNbPersonne());
    }
}
