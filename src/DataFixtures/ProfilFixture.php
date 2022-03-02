<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profil = new Profil();
        $profil->setRs('Facebook');
        $profil->setUrl('https://www.facebook.com/rasoa');
        $profil1 = new Profil();
        $profil1->setRs('Instagram');
        $profil1->setUrl('https://www.instagram.com/rakoto');
        $profil2 = new Profil();
        $profil2->setRs('Twitter');
        $profil2->setUrl('https://www.twitter.com/Vao');

        $manager->persist($profil);
        $manager->persist($profil2);
        $manager->persist($profil1);
        $manager->flush();
    }
}
