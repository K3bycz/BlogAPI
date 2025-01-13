<?php

namespace App\Controller;

use App\Service\BlogPostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    public function __construct(
        private BlogPostService $blogPostService
    ) {}

    //metoda do dashboardu, 10 najnowszych postów/blogów
    #[Route('/', name: 'blog_index')]
    public function index(): Response
    {
        $posts = $this->blogPostService->getLatestPosts(10); // pokazujemy 12 najnowszych postów

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }
    
}