<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonnesEvent;
use App\Form\PersonneType;
use App\Service\PdfService;
use App\Service\MailerService;
use App\Service\UploadService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[
    Route('/personne', name: 'personne_'),
    IsGranted('ROLE_USER')
]
class PersonneController extends AbstractController
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }
    #[
        Route('/{page<\d+>?1}/{nbr<\d+>?5}', name: 'list'),
        IsGranted('ROLE_USER')
    ]
    public function allPersonne(ManagerRegistry $doctrine, $page, $nbr): Response
    {
        $repo = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repo->count([]);
        $nbPage = ceil($nbPersonne / $nbr);
        //pagination
        $personnes = $repo->findBy([], [], $nbr, ($page - 1) * $nbr);
        $listAllPersonneEvent = new ListAllPersonnesEvent($nbPersonne);
        $this->dispatcher->dispatch($listAllPersonneEvent, ListAllPersonnesEvent::LIST_ALL_PERSONNES_EVENT);
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
    #[Route('/detail/{id<\d+>}', name: 'detail')]
    public function detail(Personne $personne = null): Response
    {
        /*$repo = $doctrine->getRepository(Personne::class);
        $personne = $repo->find($id);*/
        if (!$personne) {
            $this->addFlash('error', " la personne n'existe pas");
            return $this->redirectToRoute('personne_list');
        }
        return $this->render('personne/detail.html.twig', [
            'personne' => $personne,
        ]);
    }
    #[Route('/pdf/{id<\d+>}', name: 'pdf_detail')]
    public function generatePdf(Personne $personne = null, PdfService $pdf): Response
    {
        /*$repo = $doctrine->getRepository(Personne::class);
        $personne = $repo->find($id);*/
        if (!$personne) {
            $this->addFlash('error', " la personne n'existe pas");
            return $this->redirectToRoute('personne_list');
        }
        $html = $this->render('personne/detail.html.twig', [
            'personne' => $personne,
        ]);
        $pdf->showPdfFile($html);
    }

    #[Route('/edit/{id<\d+>?0}', name: 'edit')]
    public function addPersonne(
        Personne $personne = null,
        ManagerRegistry $doctrine,
        Request $request,
        UploadService $uploadService,
        MailerService $mail
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $new = false;
        if (!$personne) {
            $new = true;
            $personne = new Personne();
        }
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            /* UploadedFile photo */
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $directory = $this->getParameter('personne_directory');
                //call service upload image
                $file = $uploadService->uploadFileImage($photo, $directory);
                $personne->setImage($file);
            }
            if ($new) {
                $message = " a bien ??t?? ajout?? avec success";
                $personne->setCreatedBy($this->getUser());
            } else {
                $message = " a bien ??t?? modifi?? avec success";
            }
            $manager->persist($personne);
            $manager->flush();
            //EventListener
            if ($new) {
                //create event
                $addPersonneEvent = new AddPersonneEvent($personne);
                //dispatch event
                $this->dispatcher->dispatch($addPersonneEvent, AddPersonneEvent::ADD_PERSONNE_EVENT);
            }
            $this->addFlash('success', $personne->getName() . $message);
            return $this->redirectToRoute('personne_list');
        } else {
            return $this->render('personne/add.html.twig', [
                'formPersonne' => $form->createView(),
            ]);
        }
    }
    #[
        Route('/delete/{id<\d+>}', name: 'delete'),
        IsGranted('ROLE_ADMIN')
    ]
    public function delete(Personne $personne = null, ManagerRegistry $doctrine): RedirectResponse
    {
        if ($personne) {
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();
            $this->addFlash('success', " la personne a bien ??t?? supprim?? avec success");
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
            $this->addFlash('success', " la personne a bien ??t?? mis ?? jour avec success");
        } else {
            $this->addFlash('error', " la personne n'existe pas");
        }
        return $this->redirectToRoute('personne_list');
    }
}
