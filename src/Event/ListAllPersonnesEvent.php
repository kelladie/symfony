<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ListAllPersonnesEvent extends Event
{

    const LIST_ALL_PERSONNES_EVENT = 'personne.list';
    public function __construct(private int $nbPersonne)
    {
    }
    public function getNbPersonne(): int
    {
        return $this->nbPersonne;
    }
}
