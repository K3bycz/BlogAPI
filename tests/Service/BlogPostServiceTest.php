<?php

namespace App\Tests\Service;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use App\Service\BlogPostService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class BlogPostServiceTest extends TestCase
{
    private $entityManager;
    private $blogPostRepository;
    private $blogPostService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->blogPostRepository = $this->createMock(BlogPostRepository::class);

        $this->blogPostService = new BlogPostService(
            $this->entityManager,
            $this->blogPostRepository
        );
    }

    //Tworzenie tetsowego posta - test1
    public function testCreatePost()
    {
        $title = 'Post1';
        $content = 'Testowy Post';
        $imageUrl = 'http://example.com/image.jpg';

        $post = $this->createMock(BlogPost::class);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(BlogPost::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $newPost = $this->blogPostService->createPost($title, $content, $imageUrl);

        $this->assertInstanceOf(BlogPost::class, $newPost);

        $this->assertEquals($title, $newPost->getTitle());
        $this->assertEquals($content, $newPost->getContent());
        $this->assertEquals($imageUrl, $newPost->getImageUrl());
    }
    
    //Szukanie istniejących postów w bazie - test2
    public function testGetLatestPosts()
    {
        $post1 = new BlogPost();
        $post1->setTitle('Post1')->setContent('Testowy Post');
        $post2 = new BlogPost();
        $post2->setTitle('Post2')->setContent('Testowy Post');

        $this->blogPostRepository->method('findLatestPosts')
            ->willReturn([$post1, $post2]);

        $posts = $this->blogPostService->getLatestPosts(10);

        $this->assertCount(2, $posts);
        $this->assertSame('Post1', $posts[0]->getTitle());
        $this->assertSame('Post2', $posts[1]->getTitle());
    }

    //Szukanie nieistniejącego posta na blogu -test3
    public function testGetPostNotFound()
    {
        $this->blogPostRepository->method('find')
            ->willReturn(null);

        $result = $this->blogPostService->getPost(12323123314); 

        $this->assertNull($result);
    }
}