<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $datas = [
            'Aller au cinéma', 'Conduire un tracteur', 'Décorer une salle des fêtes	Faire la cuisine',
            'la pâtisserie', 'Pratiquer la spéléologie', 'Participer à un forum de discussion sur un thème',
            'Participer à la rénovation ', 'faire des BD', 'tagger', 'Aider un enfant à faire ses devoirs',
            'faire une compétition sportive', 'Pratiquer un sport régulièrement', 'Être bénévole dans une association ', 'Créer un maquillage pour le cinéma', 'la télé',
            'une fête', 'Participer à un raid'
        ];
        for ($i = 0; $i < count($datas); $i++) {
            $hobby = new Hobby();
            $hobby->setDesignation($datas[$i]);
            $manager->persist($hobby);
        }
        $manager->flush();
    }
}
