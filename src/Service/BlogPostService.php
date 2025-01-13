<?php

namespace App\Service;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;

class BlogPostService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BlogPostRepository $blogPostRepository
    ) {}

    //pobieranie postów/blogów z bazy
    public function getAllPosts(): array
    {
        return $this->blogPostRepository->findAll();
    }

    public function getPost(int $id): ?BlogPost
    {
        return $this->blogPostRepository->find($id);
    }

    public function getLatestPosts(int $limit = 10): array
    {
        return $this->blogPostRepository->findLatestPosts($limit);
    }

    //tworzenie posta
    public function createPost(string $title, string $content, ?string $imageUrl = null): BlogPost
    {
        $post = new BlogPost();
        $post->setTitle($title)
            ->setContent($content)
            ->setImageUrl($imageUrl);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

}