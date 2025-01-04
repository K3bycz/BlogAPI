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

    #[Route('/', name: 'blog_index')]
    public function index(): Response
    {
        $posts = $this->blogPostService->getLatestPosts(12); // pokazujemy 12 najnowszych postów
        $totalPosts = $this->blogPostService->getTotalPosts();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'totalPosts' => $totalPosts
        ]);
    }

    #[Route('/search', name: 'blog_search')]
    public function search(Request $request): Response
    {
        $query = $request->query->get('q', '');
        $posts = [];
        
        if ($query) {
            $posts = $this->blogPostService->searchByTitle($query);
        }

        return $this->render('blog/search.html.twig', [
            'posts' => $posts,
            'query' => $query
        ]);
    }
    
}