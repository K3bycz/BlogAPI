<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    /**
     * @return BlogPost[]
     */
    public function findLatestPosts(int $limit = 10): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return BlogPost[]
     */
    public function findByTitle(string $title): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('LOWER(b.title) LIKE LOWER(:title)')
            ->setParameter('title', '%' . $title . '%')
            ->orderBy('b.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countPosts(): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}