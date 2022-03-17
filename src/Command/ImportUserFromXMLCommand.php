<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class ImportUserFromXMLCommand extends Command
{
    private EntityManagerInterface $manager;
    private KernelInterface $appKernel;

    public function __construct(EntityManagerInterface $manager, KernelInterface $appKernel)
    {
        $this->manager = $manager;
        $this->appKernel = $appKernel;

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

        // Create a new serializer
        $serializer = new Serializer(
            [new GetSetMethodNormalizer(), new ArrayDenormalizer()],
            [new XmlEncoder()]
        );

        // Create a new directory to store XML files
        $projectDir = $this->appKernel->getProjectDir();
        $uploadDir = $projectDir.'/public/uploads';

        $io->section('Import users');
        $this->importUsers($userRepository, $io, $serializer, $uploadDir);

        return Command::SUCCESS;
    }

    private function importUsers(
        UserRepository $repository,
        SymfonyStyle $io,
        Serializer $serializer,
        string $uploadDir
    ): void {
        // File where data will be stored
        $file = sprintf('%s/%s', $uploadDir, 'users.xml');

        $data = file_get_contents($file);

        $users = $serializer->deserialize($data, 'App\Entity\User[]', 'xml', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['roles']]);

        $io->progressStart(count($users));

        foreach ($users as $user) {
            $existingUser = $repository->findOneBy(['email' => $user->getEmail()]);

            if($existingUser === null) {
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
