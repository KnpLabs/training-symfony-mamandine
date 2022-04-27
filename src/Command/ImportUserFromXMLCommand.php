<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\Importer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportUserFromXMLCommand extends Command
{
    private EntityManagerInterface $manager;
    private Importer $importer;

    public function __construct(EntityManagerInterface $manager, Importer $importer)
    {
        $this->manager = $manager;
        $this->importer = $importer;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:import-users-from-xml')
            ->setDescription('Database user import from XML file (user.xml)')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Skip the confirmation dialog.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if (false === $input->getOption('force')) {
            $confirm = $io->confirm('This command will import user from XML file. Do you want to continue?');

            if (!$confirm) {
                $output->writeln('<info>Aborted by user.</info>');

                return Command::FAILURE;
            }
        }

        $userRepository = $this->manager->getRepository(User::class);

        $io->section('Import users');
        $this->importUsers($userRepository, $io);

        return Command::SUCCESS;
    }

    private function importUsers(
        UserRepository $repository,
        SymfonyStyle $io
    ): void {
        $users = $this->importer->importUsers();

        $io->progressStart(count($users));

        foreach ($users as $user) {
            $existingUser = $repository->findOneBy(['email' => $user->getEmail()]);

            if($existingUser === null) {
                usleep(500000);
                $user->setRoles(['ROLE_USER']);

                $this->manager->persist($user);
            }

            $io->progressAdvance();
        }

        $this->manager->flush();
        $this->manager->clear();

        $io->progressFinish();
    }
}
