<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $datas = [
            'Rédacteur', 'traducteur', 'secrétaire', 'opérateur de saisie ou écrivain',
            'Blogueur ou créateur de sites internet', 'Youtubeur', 'Téléprospecteur ou télévendeur indépendant', 'Assistant maternel ou assistant familial',
            'Maquilleur', 'prothésiste ongulaire ou coiffeur', 'Coach', 'ophrologue', 'professeur ou formateur', 'Parieur ou joueur professionnel',
            'Toiletteur', 'éducateur ou baby-sitter pour animaux', 'Cuisinier ou pâtissier', 'Couturier ou repasseur'
        ];
        for ($i = 0; $i < count($datas); $i++) {
            $job = new Job();
            $job->setDesignation($datas[$i]);
            $manager->persist($job);
        }
        $manager->flush();
    }
}
