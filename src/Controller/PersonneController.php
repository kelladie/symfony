<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personne', name: 'personne_')]
class PersonneController extends AbstractController
{
    #[Route('/{page<\d+>?1}/{nbr<\d+>?5}', name: 'list')]
    public function allPersonne(ManagerRegistry $doctrine, $page, $nbr): Response
    {
        $repo = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repo->count([]);
        $nbPage = ceil($nbPersonne / $nbr);
        //pagination
        $personnes = $repo->findBy([], [], $nbr, ($page - 1) * $nbr);
        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'isPaginated' => true,
            'nbPersonne' => $nbPersonne,
            'nbPage' => $nbPage,
            'page' => $page,
            'nbr' => $nbr
        ]);
    }
    #[Route('/alls/age/{ageMin<\d+>}/{ageMax<\d+>}', name: 'list_age')]
    public function personneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repo = $doctrine->getRepository(Personne::class);
        $personnes = $repo->findPersonnesByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/index.html.twig', ['personnes' => $personnes]);
    }
    #[Route('/{id<\d+>}', name: 'detail')]
    public function detail(Personne $personne = null): Response
    {
        /*$repo = $doctrine->getRepository(Personne::class);
        $personne = $repo->find($id);*/
        if (!$personne) {
            $this->addFlash('error', " la personne n'existe pas");
            return $this->redirectToRoute('personne_list');
        }
        return $this->render('personne/detail.html.twig', [
            'user' => $personne,
        ]);
    }
    #[Route('/add', name: 'add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $personne->setFirstName('Rabenja');
        $personne->setName('MiarinaSoa');
        $personne->setAge(20);
        $entityManager->persist($personne);

        $entityManager->flush();
        return $this->render('personne/detail.html.twig', [
            'user' => $personne,
        ]);
    }
    #[Route('/delete/{id<\d+>}', name: 'delete')]
    public function delete(Personne $personne = null, ManagerRegistry $doctrine): RedirectResponse
    {
        if ($personne) {
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();
            $this->addFlash('success', " la personne a bien été supprimé avec success");
        } else {
            $this->addFlash('error', " la personne n'existe pas");
        }
        return $this->redirectToRoute('personne_list');
    }
    #[Route('/update/{id<\d+>}/{name}/{firstname}/{age}', name: 'update')]
    public function update(Personne $personne = null, ManagerRegistry $doctrine, $name, $firstname, $age): RedirectResponse
    {
        if ($personne) {
            $personne->setName($name);
            $personne->setFirstName($firstname);
            $personne->setAge($age);
            $manager = $doctrine->getManager();
            $manager->flush();
            $this->addFlash('success', " la personne a bien été mis à jour avec success");
        } else {
            $this->addFlash('error', " la personne n'existe pas");
        }
        return $this->redirectToRoute('personne_list');
    }
}
