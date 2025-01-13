<?php

namespace App\Formatter;

use App\Entity\BlogPost;

class BlogPostFormatter
{
    /**
     * Formatowanie obiektu BlogPost do tablicy asocjacyjnej
     *
     * @param BlogPost $blogPost
     * @return array
     */
    public function format(BlogPost $blogPost): array
    {
        return [
            'id' => $blogPost->getId(),
            'title' => $blogPost->getTitle(),
            'content' => $blogPost->getContent(),
            'createdAt' => $blogPost->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $blogPost->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Formatowanie kolekcji BlogPost do tablicy
     *
     * @param iterable $blogPosts
     * @return array
     */
    public function formatCollection(iterable $blogPosts): array
    {
        $formattedBlogPosts = [];

        foreach ($blogPosts as $blogPost) {
            $formattedBlogPosts[] = $this->format($blogPost);
        }

        return $formattedBlogPosts;
    }
}
