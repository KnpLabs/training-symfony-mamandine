<?php

namespace App\Services;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class Importer
{
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;

        // Create a new serializer
        $this->serializer = new Serializer(
            [new GetSetMethodNormalizer(), new ArrayDenormalizer()],
            [new XmlEncoder()]
        );

        // Upload directory
        $projectDir = $this->kernel->getProjectDir();
        $this->uploadDir = $projectDir.'/public/uploads';
    }

    public function importUsers(): array
    {
        // File where data will be stored
        $file = sprintf('%s/%s', $this->uploadDir, 'users.xml');

        $data = file_get_contents($file);

        $users = $this->serializer->deserialize($data, 'App\Entity\User[]', 'xml', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['roles']]);

        return $users;
    }

    public function importCakes()
    {
        // File where data will be stored
        $file = sprintf('%s/%s', $this->uploadDir, 'cakes.xml');

        $data = file_get_contents($file);

        $users = $this->serializer->deserialize($data, 'App\Entity\Cake[]', 'xml', []);

        return $users;
    }
}
