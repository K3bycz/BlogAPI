<?php

namespace App\Controller\Api;

use App\Entity\BlogPost;
use App\Entity\Group;
use App\Entity\User;
use App\Formatter\BlogPostFormatter;
use App\Formatter\UserFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //Metoda sprawdzająca grupe użytkownika
    private function isUserInGroup($user, $groupName): bool 
    { 
        foreach ($user->getGroups() as $group) 
        { 
            if ($group->getName() === $groupName) 
            { 
                return true; 
            } 
        }

        return false; 
    }

    //Metoda testowa połączenia
    #[Route('/test', name: 'test', methods: ['GET'])]
    public function test(): Response
    {
        return $this->json([
            'message' => 'Połączenie z api działa :)',
            'status' => 'success',
            'data' => null,
        ]);
    }

    //Dodawanie postów do bazy
    #[Route('/blog/create', name: 'api_create_blog', methods: ['POST'])]
    public function createBlog(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json([
                'error' => 'Brak danych do logowania!'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            return $this->json([
                'error' => 'Nieprawidłowe dane logowania!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$this->isUserInGroup($user, 'Admin')) {
            return $this->json([
                'error' => 'Dostęp zabroniony - nie masz uprawnień na dodawanie postów'
            ], Response::HTTP_FORBIDDEN);
        }

        if (!isset($data['title']) || !isset($data['content'])) {
            return $this->json([
                'error' => 'Tytuł i opis są wymagane!'
            ], Response::HTTP_BAD_REQUEST);
        }

        $blogPost = new BlogPost();
        $blogPost->setTitle($data['title'])
            ->setContent($data['content'])
            ->setImageUrl($data['imageUrl'] ?? null)
            ->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($blogPost);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Blog został utworzony pomyślnie',
            'status' => 'success'
        ], Response::HTTP_CREATED);
    }

    //Wyświetlanie postów z bazy
    #[Route('/blog/posts', name: 'api_get_posts', methods: ['GET'])]
    public function getPosts(BlogPostFormatter $formatter): Response
    {
        $blogPosts = $this->entityManager->getRepository(BlogPost::class)->findAll();
    
        // Formatowanie wyników za pomocą formattera
        $postsData = $formatter->formatCollection($blogPosts);
    
        return $this->json([
            'message' => 'Pomyślnie pobrano posty',
            'status' => 'success',
            'data' => $postsData,
        ], Response::HTTP_OK);
    }

    //Usuwanie postów z bazy
    #[Route('/blog/post/delete', name: 'api_delete_post', methods: ['DELETE'])]
    public function deletePost(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $postId = $data['id'] ?? null;
    
        if (!$postId) {
            return $this->json(['error' => 'Nie podano ID posta'], Response::HTTP_BAD_REQUEST);
        }
    
        $post = $this->entityManager->getRepository(BlogPost::class)->find($postId);
    
        if (!$post) {
            return $this->json(['error' => 'Post o podanym ID nie istnieje'], Response::HTTP_NOT_FOUND);
        }
    
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    
        return $this->json(['message' => 'Post został usunięty'], Response::HTTP_OK);
    }
    
    //Pobieranie informacji o użytkownikach z systemu
    #[Route('/users', name: 'api_get_users', methods: ['GET'])]
    public function getUsers(UserFormatter $formatter): Response
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        if (!$users) {
            return $this->json([
                'message' => 'Nie znaleziono żadnych użytkowników',
                'status' => 'error',
                'data' => []
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'message' => 'Pomyślnie pobrano dane użytkowników',
            'status' => 'success',
            'data' => $formatter->formatCollection($users),
        ], Response::HTTP_OK);
    }

}
