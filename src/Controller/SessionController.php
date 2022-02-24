<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo', name: 'todo_')]
class SessionController extends AbstractController
{
    #[Route('/session', name: 'session')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if ($session->has('nbVisite')) {
            $nbrvisite = $session->get('nbVisite') + 1;
        } else {
            $nbrvisite = 1;
        }
        $session->set('nbVisite', $nbrvisite);
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }
    #[Route('/', name: 'session')]
    public function todo(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('todos')) {
            $todos = [
                "achat" => "achat de clé",
                "cours" => "finaliser mon cours",
                "correction" => "correction des examens"
            ];

            $session->set('todos', $todos);
            $this->addFlash('info', "la liste de votre todos vient d'être initialisée");
        }
        return $this->render('session/todo.html.twig');
    }
    #[Route('/add/{name}/{content}', name: 'add', defaults: ['name' => 'lundi', 'content' => 'contentlundi'])]
    public function addTodo(Request $request, $name, $content): RedirectResponse
    {
        $session = $request->getSession();
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                $this->addFlash('error', "le todo id $name existe déjà dans la liste");
            } else {
                $todos[$name] = $content;
                $this->addFlash('success', "le todo id $name a bien été ajouté avec succes");
                $session->set('todos', $todos);
            }
        } else {
            $this->addFlash('error', "la liste de todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo_session');
    }
    #[Route('/update/{name}/{content}', name: 'update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse
    {
        $session = $request->getSession();
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                $this->addFlash('error', "le todo id $name n'existe pas dans la liste");
            } else {
                $todos[$name] = $content;
                $this->addFlash('success', "le todo id $name a bien été modifié avec succes");
                $session->set('todos', $todos);
            }
        } else {
            $this->addFlash('error', "la liste de todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo_session');
    }
    #[Route('/delete/{name}', name: 'delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse
    {
        $session = $request->getSession();
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                $this->addFlash('error', "le todo id $name n'existe pas dans la liste");
            } else {
                unset($todos[$name]);
                $this->addFlash('success', "le todo id $name a bien été modifié avec succes");
                $session->set('todos', $todos);
            }
        } else {
            $this->addFlash('error', "la liste de todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo_session');
    }
    #[Route('/reset', name: 'reset')]
    public function resetTodo(Request $request): RedirectResponse
    {
        $session = $request->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('todo_session');
    }
}
