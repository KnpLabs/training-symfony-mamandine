<?php

namespace App\Command;

use App\Entity\Cake;
use App\Entity\User;
use App\Repository\CakeRepository;
use App\Repository\UserRepository;
use App\Services\Importer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCakeFromXMLCommand extends Command
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
            ->setName('app:import-cakes-from-xml')
            ->setDescription('Database cakes import from XML file (cakes.xml)')
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

        $cakeRepository = $this->manager->getRepository(Cake::class);

        $io->section('Import cakes');
        $this->importCakes($cakeRepository, $io);

        return Command::SUCCESS;
    }

    private function importCakes(
        CakeRepository $repository,
        SymfonyStyle $io
    ): void {
        $cakes = $this->importer->importCakes();

        $io->progressStart(count($cakes));

        foreach ($cakes as $cake) {
            $existingCake = $repository->findOneBy(['name' => $cake->getName()]);

            if($existingCake === null) {
                usleep(500000);

                $this->manager->persist($cake);
            }

            $io->progressAdvance();
        }

        $this->manager->flush();
        $this->manager->clear();

        $io->progressFinish();
    }
}
