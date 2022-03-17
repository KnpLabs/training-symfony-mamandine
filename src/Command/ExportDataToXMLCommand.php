<?php

namespace App\Command;

use App\Entity\Cake;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\CakeRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ExportDataToXMLCommand extends Command
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
            ->setName('app:export-xml-data')
            ->setDescription('Database exportation to XML files (cakes.xml, categories.xml, users.xml)')
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
            $confirm = $io->confirm('This command will export current database tables to XML files. Do you want to continue?');

            if (!$confirm) {
                $output->writeln('<info>Aborted by user.</info>');

                return Command::FAILURE;
            }
        }

        $cakeRepository = $this->manager->getRepository(Cake::class);
        $categoryRepository = $this->manager->getRepository(Category::class);
        $userRepository = $this->manager->getRepository(User::class);

        // Create a new serializer
        $encoders = [new XmlEncoder()];
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getName();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $normalizers = [$normalizer];
        $serializer = new Serializer($normalizers, $encoders);


        // Create a new directory to store XML files
        $projectDir = $this->appKernel->getProjectDir();
        $uploadDir = $projectDir.'/public/uploads';
        $filesystem = new Filesystem();
        $filesystem->mkdir($uploadDir);

        $io->section('Export cakes');
        $this->exportCakes($cakeRepository, $io, $filesystem, $serializer, $uploadDir);

        $io->section('Export categories');
        $this->exportCategories($categoryRepository, $io, $filesystem, $serializer, $uploadDir);

        $io->section('Export users');
        $this->exportUsers($userRepository, $io, $filesystem, $serializer, $uploadDir);

        return Command::SUCCESS;
    }

    private function exportCakes(
        CakeRepository $repository,
        SymfonyStyle $io,
        Filesystem $filesystem,
        Serializer $serializer,
        string $uploadDir
    ): void {
        $cakes = $repository->findAll();

        // File where data will be stored
        $file = sprintf('%s/%s', $uploadDir, 'cakes.xml');

        $io->progressStart(count($cakes));

        usleep(500000);
        $xmlContent = $serializer->serialize($cakes, 'xml');

        $filesystem->remove($file);
        $filesystem->touch($file);
        $filesystem->appendToFile($file, $xmlContent);

        $io->progressAdvance();

        $io->progressFinish();
    }

    private function exportCategories(
        CategoryRepository $repository,
        SymfonyStyle $io,
        Filesystem $filesystem,
        Serializer $serializer,
        string $uploadDir
    ): void {
        $categories = $repository->findAll();

        // File where data will be stored
        $file = sprintf('%s/%s', $uploadDir, 'categories.xml');

        $io->progressStart(count($categories));

        usleep(500000);
        $xmlContent = $serializer->serialize($categories, 'xml');

        $filesystem->remove($file);
        $filesystem->touch($file);
        $filesystem->appendToFile($file, $xmlContent);

        $io->progressAdvance();

        $io->progressFinish();
    }

    private function exportUsers(
        UserRepository $repository,
        SymfonyStyle $io,
        Filesystem $filesystem,
        Serializer $serializer,
        string $uploadDir
    ): void {
        $users = $repository->findAll();

        // File where data will be stored
        $file = sprintf('%s/%s', $uploadDir, 'users.xml');

        $io->progressStart(count($users));

        usleep(500000);
        $xmlContent = $serializer->serialize($users, 'xml');

        $filesystem->remove($file);
        $filesystem->touch($file);
        $filesystem->appendToFile($file, $xmlContent);

        $io->progressAdvance();

        $io->progressFinish();
    }
}
