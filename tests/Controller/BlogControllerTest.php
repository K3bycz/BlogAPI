<?php

namespace App\Tests\Controller;

use App\Controller\BlogController;
use App\Entity\BlogPost;
use App\Form\BlogPostForm;
use App\Service\BlogPostService;
use App\Entity\User;
use App\Entity\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class BlogControllerTest extends TestCase
{
    private BlogPostService $blogPostService;
    private BlogController $controller;
    private Environment $twig;
    private ContainerInterface $container;
    private FormFactoryInterface $formFactory;
    private UrlGeneratorInterface $urlGenerator;

    protected function setUp(): void
    {
        $this->blogPostService = $this->createMock(BlogPostService::class);
        
        $this->twig = $this->createMock(Environment::class);
        
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container
            ->method('has')
            ->willReturn(true);
        $this->container
            ->method('get')
            ->willReturnMap([
                ['twig', 1, $this->twig],
                ['form.factory', 1, $this->formFactory],
                ['router', 1, $this->urlGenerator]
            ]);
        
        $this->controller = new BlogController($this->blogPostService);
        $this->controller->setContainer($this->container);
    }

    //Przekazywanie tablicy z postami do widoku - test1
    public function testIndex(): void
    {
        $expectedPosts = [
            ['id' => 1, 'title' => 'Post 1', 'content' => 'Testowy Post na blogu'],
            ['id' => 2, 'title' => 'Post 2', 'content' => 'Testowy Post na blogu'],
            ['id' => 3, 'title' => 'Post 3', 'content' => 'Testowy Post na blogu']
        ];

        $this->blogPostService
            ->expects($this->once())
            ->method('getLatestPosts')
            ->with(10)
            ->willReturn($expectedPosts);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with(
                'blog/index.html.twig',
                ['posts' => $expectedPosts]
            )
            ->willReturn('rendered content');

        $response = $this->controller->index();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    //Przekazywanie pustej tablicy postów do widoku - test2
    public function testIndexWithEmptyPosts(): void
    {
        $this->blogPostService
            ->expects($this->once())
            ->method('getLatestPosts')
            ->with(10)
            ->willReturn([]);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with(
                'blog/index.html.twig',
                ['posts' => []]
            )
            ->willReturn('rendered content');

        $response = $this->controller->index();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    //Dodawanie nowego posta - test3
    public function testNewPost(): void
    {
        $form = $this->createMock(FormInterface::class);
        $formView = $this->createMock(FormView::class);

        $this->urlGenerator
            ->expects($this->once())
            ->method('generate')
            ->with('create_blog')
            ->willReturn('/blog/create');

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->with(
                BlogPostForm::class,
                $this->isInstanceOf(BlogPost::class),
                [
                    'action' => '/blog/create',
                    'method' => 'POST'
                ]
            )
            ->willReturn($form);

        $form
            ->expects($this->once())
            ->method('createView')
            ->willReturn($formView);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with(
                'blog/new.html.twig',
                ['blogPostForm' => $formView]
            )
            ->willReturn('rendered form template');

        $response = $this->controller->new();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

}