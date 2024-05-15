<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use App\Form\DoneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/todo")
 */
class TodoController extends AbstractController
{
    /**
     * @Route("/", name="app_todo_index", methods={"GET", "POST"})
     */
    public function index(Request $request, TodoRepository $todoRepository): Response
    {
        $form = $this->createForm(DoneType::class);
        $form->handleRequest($request);
        $data = $form->getData();
        $etat = false;
        $searchTerms = false;
        if(isset($data["done"])){
            $etat = $data["done"];
        }
        $searchTerms = $form->get('searchTerms')->getData();
        
        if (isset($_REQUEST["OrderBy"]) && isset($_REQUEST["Order"])){
            $orderBy = $_REQUEST["OrderBy"];
            $order = $_REQUEST["Order"];
            if ($order == "ASC"){
                $orderSort = "DESC";
            } else {
                $orderSort = "ASC";
            }
        } else {
            $orderBy = "name";
            $order = "ASC";
            $orderSort = "DESC";
        }
        return $this->render('todo/index.html.twig', [
            'varord' => $orderSort,
            'todos' => $todoRepository->findByOrdered($searchTerms,$orderBy,$order,$etat),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="app_todo_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TodoRepository $todoRepository): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->add($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/new.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/search", name="app_todo_search", methods={"POST"})
     */
    public function search(Request $request, TodoRepository $todoRepository): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $searchData = $requestData['search'];
        $results = $todoRepository->findTodoBySearch($searchData);
        return $this->json($results);
    }

    /**
     * @Route("/check", name="app_todo_check", methods={"POST"})
     */
    public function check(Request $request, TodoRepository $todoRepository): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $checkData = $requestData['check'];
        if($checkData === "true"){
            $results = $todoRepository->findTodoByCheck();
        }else{
            $results = $todoRepository->findAll();
        }
        return $this->json($results);
    }

    /**
     * @Route("/{id}", name="app_todo_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Todo $todo): Response
    {
        return $this->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_todo_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->add($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_todo_delete", methods={"POST"})
     */
    public function delete(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->request->get('_token'))) {
            $todoRepository->remove($todo, true);
        }

        return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/done/{id}", name="app_todo_done", methods={"GET"})
     */
    public function done(Request $request, Todo $todo, TodoRepository $todoRepository, EntityManagerInterface $em): Response
    {
        $todo->setDone(!$todo->isDone());
        $em->persist($todo);
        $em->flush();
        return $this->json(["Sucess",$todo->isDone()]);
    }
}
