<?php

namespace App\Tests\Controller;

use App\Controller\UserController;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Group;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserControllerTest extends TestCase
{
    private $userPasswordHasher;
    private $userRepository;
    private $twig;
    private $container;
    private $controller;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->twig = $this->createMock(Environment::class);
        $this->container = $this->createMock(ContainerInterface::class);

        $this->container
            ->method('has')
            ->willReturn(true);
        $this->container
            ->method('get')
            ->willReturnMap([
                ['twig', 1, $this->twig],
            ]);

        $this->controller = new UserController();
        $this->controller->setContainer($this->container);
    }

    //Przekazywanie tablicy z użytkownikami do widoku - test1
    public function testIndex(): void
    {
        $expectedUsers = [
            ['id' => 1, 'username' => 'Antek', 'email' => 'antek@gmail.com'],
            ['id' => 2, 'username' => 'Marek', 'email' => 'marek@gmail.com'],
            ['id' => 3, 'username' => 'Wojtek', 'email' => 'wojtek@gmail.com']
        ];

        $this->userRepository
            ->expects($this->once())
            ->method('findAllUsers')
            ->willReturn($expectedUsers);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with(
                'user/index.html.twig',
                ['users' => $expectedUsers]
            )
            ->willReturn('rendered content');

        $response = $this->controller->index($this->userRepository);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    //Przekazywanie pustej tablicy do widoku - test2
    public function testIndexWithZeroUsers(): void
    {
        $this->userRepository
            ->expects($this->once())
            ->method('findAllUsers')
            ->willReturn([]);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with(
                'user/index.html.twig',
                ['users' => []]
            )
            ->willReturn('rendered content');

        $response = $this->controller->index($this->userRepository);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}