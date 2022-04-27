<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Exporter
{
    public function __construct(KernelInterface $kernel, EntityManagerInterface $manager)
    {
        $this->kernel = $kernel;
        $this->manager = $manager;

        // Create a new serializer
        $encoders = [new XmlEncoder()];
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getName();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $normalizers = [$normalizer];
        $this->serializer = new Serializer($normalizers, $encoders);

        // Upload directory
        $projectDir = $this->kernel->getProjectDir();
        $this->uploadDir = $projectDir.'/public/uploads';
        $filesystem = new Filesystem();
        $filesystem->mkdir($this->uploadDir);
        $this->filesystem = $filesystem;
    }

    public function exportUsers(): array
    {
        $userRepository = $this->manager->getRepository(User::class);
        $users = $userRepository->findAll();

        // File where data will be stored
        $file = sprintf('%s/%s', $this->uploadDir, 'users.xml');

        usleep(500000);
        $xmlContent = $this->serializer->serialize($users, 'xml');

        $this->filesystem->remove($file);
        $this->filesystem->touch($file);
        $this->filesystem->appendToFile($file, $xmlContent);

        return $users;
    }
}
