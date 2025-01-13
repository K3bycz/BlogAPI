<?php

namespace App\Command;

use App\Entity\BlogPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class AddBlogPostCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setName('app:add-blog')
            ->setDescription('Adds a new blog post')
            ->addArgument('title', InputArgument::REQUIRED, 'The title of the blog post')
            ->addArgument('content', InputArgument::REQUIRED, 'The content of the blog post')
            ->addArgument('imageUrl', InputArgument::OPTIONAL, 'The URL of the image for the blog post');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Pobieranie danych z argumentów komendy
        $title = $input->getArgument('title');
        $content = $input->getArgument('content');
        $imageUrl = $input->getArgument('imageUrl');


        $blogPost = new BlogPost();
        $blogPost->setTitle($title);
        $blogPost->setContent($content);
        $blogPost->setImageUrl($imageUrl);
        $blogPost->setUpdatedAt(new \DateTimeImmutable());


        // Zapis do bazy
        $this->entityManager->persist($blogPost);
        $this->entityManager->flush();

        $output->writeln('Twój wpis do bloga został zapisany!');

        return Command::SUCCESS;
    }
}
