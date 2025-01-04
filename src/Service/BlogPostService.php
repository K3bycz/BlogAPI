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

    public function getAllPosts(): array
    {
        return $this->blogPostRepository->findAll();
    }

    public function getPost(int $id): ?BlogPost
    {
        return $this->blogPostRepository->find($id);
    }

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

    public function getLatestPosts(int $limit = 10): array
    {
        return $this->blogPostRepository->findLatestPosts($limit);
    }

    public function searchByTitle(string $title): array
    {
        return $this->blogPostRepository->findByTitle($title);
    }

    public function getTotalPosts(): int
    {
        return $this->blogPostRepository->countPosts();
    }
}