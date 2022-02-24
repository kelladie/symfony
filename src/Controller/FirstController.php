<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/first', name: 'first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'controller_name' => 'FirstController',
            'path' => 'test.jpg',
        ]);
    }
    #[Route('/tabUsers', name: 'tab_users')]
    public function tabUsers(): Response
    {
        $users = [
            ['firstName' => 'Soa', 'Name' => 'Bozy', 'Age' => 10],
            ['firstName' => 'MiSoa', 'Name' => 'lita', 'Age' => 15],
            ['firstName' => 'SoaNala', 'Name' => 'Vao', 'Age' => 20],
        ];
        return $this->render('first/tabUsers.html.twig', ['users' => $users]);
    }
    #[Route('/firstTemplate', name: 'first_template')]
    public function firstTemplate(): Response
    {
        return $this->render('template.html.twig');
    }
}
