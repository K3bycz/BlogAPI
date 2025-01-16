<?php

namespace App\Command;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:add-admin',
    description: 'Dodaj użytkownika do grupy administartorów',
)]
class AddAdminCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email użytkownika, którego chcesz dodać do grupy Administratorów:');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');

        // Szukanie użytkownika po mailu
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $output->writeln('<error>Nie znalezionego użytkownika z emailem: ' . $email . '</error>');
            return Command::FAILURE;
        }

        // Szukanie grupy Admin
        $adminGroup = $this->entityManager->getRepository(Group::class)->findOneBy(['name' => 'Admin']);

        if (!$adminGroup) {
            // Tworzenie grupy jak nie istnieje
            $adminGroup = new Group('Admin');
            $this->entityManager->persist($adminGroup);
            $output->writeln('<info>Grupa administratorów utworzona.</info>');
        }

        // Dodawanie użytkownika do grupy Adminów
        $user->addGroup($adminGroup);

        $roles = $user->getRoles();
        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_ADMIN';
            $user->setRoles($roles);
            $output->writeln('<info>Dodano rolę ROLE_ADMIN użytkownikowi.</info>');
        } else {
            $output->writeln('<comment>Użytkownik już posiada rolę ROLE_ADMIN.</comment>');
        }
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>Użytkownik o mailu: ' . $email . ' został dodany do grupy administratorów.</info>');

        return Command::SUCCESS;
    }
}
