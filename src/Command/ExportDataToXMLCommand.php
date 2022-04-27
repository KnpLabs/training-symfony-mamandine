<?php

namespace App\Command;

use App\Services\Exporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExportDataToXMLCommand extends Command
{
    private Exporter $exporter;

    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;

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

        $io->section('Export users');
        $this->exportUsers($io);

        return Command::SUCCESS;
    }

    private function exportUsers(
        SymfonyStyle $io
    ): void {

        $users = $this->exporter->exportUsers();

        $io->progressStart(count($users));

        $io->progressAdvance();

        $io->progressFinish();
    }
}
