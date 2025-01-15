<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Service\BlogPostService;
use App\Exception\BlogAccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use App\Form\BlogPostForm;

class BlogController extends AbstractController
{
    public function __construct(
        private BlogPostService $blogPostService
    ) {}

    //metoda do dashboardu, 10 najnowszych postów/blogów
    #[Route('/', name: 'blog_index')]
    public function index(): Response
    {
        $posts = $this->blogPostService->getLatestPosts(10);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    //metoda na wyświetlanie formularza dodawania postów
    #[Route('/blog/new', name: 'blog_new')]
    public function new(): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostForm::class, $blogPost, [
            'action' => $this->generateUrl('create_blog'),
            'method' => 'POST',
        ]);
    
        return $this->render('blog/new.html.twig', [
            'blogPostForm' => $form->createView(),
        ]);
    }
   
    //metoda zapisywania blogów do bazy
    #[Route('/blog/create', name: 'create_blog', methods: ['POST'])]
    public function createBlog(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
    
        // Sprawdzenie uprawnień użytkownika
        if (!$user || !$user->getGroups()->exists(fn($key, $group) => $group->getName() === 'Admin')) {
            return $this->json([
                'error' => 'Dostęp zabroniony - nie masz uprawnień na dodawanie postów'
            ], Response::HTTP_FORBIDDEN);
        }
    
        // Dane z Formularza
        $form = $this->createForm(BlogPostForm::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var BlogPost $blogPost */
            $blogPost = $form->getData();
            $blogPost->setUpdatedAt(new \DateTimeImmutable());
    
            $entityManager->persist($blogPost);
            $entityManager->flush();
    
            return $this->redirectToRoute('blog_index');
        }
    
        // Dane z JSONA
        $data = json_decode($request->getContent(), true);
    
        if (is_array($data)) {
            if (empty($data['title']) || empty($data['content'])) {
                return $this->json([
                    'error' => 'Tytuł i opis są wymagane!'
                ], Response::HTTP_BAD_REQUEST);
            }
    
            $blogPost = new BlogPost();
            $blogPost->setTitle($data['title'])
                ->setContent($data['content'])
                ->setImageUrl($data['imageUrl'] ?? null)
                ->setUpdatedAt(new \DateTimeImmutable());
    
            $entityManager->persist($blogPost);
            $entityManager->flush();
    
            return $this->redirectToRoute('blog_index');

        }
    
        // Jeśli nic nie zostało przesłane lub dane są nieprawidłowe
        return $this->json([
            'error' => 'Niepoprawność danych!'
        ], Response::HTTP_BAD_REQUEST);
    }
}