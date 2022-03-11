<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserAnonymizationCommand extends Command
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:user-anonymization')
            ->setDescription('This command will change every sensitive data according GDPR for each user on our database')
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
            $confirm = $io->confirm('This command will update the database replacing personal user\'s data with fake ones. Do you want to continue?');

            if (!$confirm) {
                $output->writeln('<info>Aborted by user.</info>');

                return Command::FAILURE;
            }
        }

        $userRepository = $this->manager->getRepository(User::class);

        $io->section('Anonymize users');
        $this->anonymizeUsers($userRepository, $io);

        return Command::SUCCESS;
    }

    private function anonymizeUsers(
        UserRepository $repository,
        SymfonyStyle $io
    ): void {
        $faker = FakerFactory::create('fr_FR');
        $users = $repository->findAll();

        $users = array_filter($users, function (User $user) {
            return !in_array('ROLE_ADMIN', $user->getRoles());
         });

        $io->progressStart(count($users));

        foreach ($users as $user) {
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setNationality($faker->country());
            $user->setEmail($faker->email());

            usleep(500000);
            $io->progressAdvance();
        }

        $this->manager->flush();
        $this->manager->clear();

        $io->progressFinish();
    }
}
