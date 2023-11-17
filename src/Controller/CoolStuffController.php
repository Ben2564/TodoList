<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CoolStuffController extends AbstractController
{
    /**
     * @Route("/cool/stuff", name="app_cool_stuff")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CoolStuffController.php',
        ]);
    }

    /**
     * @Route("/blog/{page}", name="app_blog_page", requirements={"page"="\d+"})
     */
    public function blogPage($page): JsonResponse
    {
        return $this->json("page ". $page);
    }

    /**
     * @Route("/blog/{slug}", name="app_blog_slug", requirements={"slug"="[a-z]+"})
     */
    public function blogSlug($slug): JsonResponse
    {
        return $this->json($slug);
    }

    /**
     * @Route("/blog/", name="app_blog")
     */
    public function blog(): JsonResponse
    {
        return $this->json("<h1>Liste des articles</h1>");
    }
}
