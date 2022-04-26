<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends AbstractController
{

    public function import()
    {
        return $this->render('cake/import.html.twig', []);
    }

    public function importUsers(EntityManagerInterface $manager, KernelInterface $appKernel)
    {
        // Create a new directory to store XML files
        $projectDir = $appKernel->getProjectDir();
        $uploadDir = $projectDir.'/public/uploads';
        $file = sprintf('%s/%s', $uploadDir, 'users.xml');

        $data = file_get_contents($file);

        $userRepository = $manager->getRepository(User::class);

        // Create a new serializer
        $serializer = new Serializer(
            [new GetSetMethodNormalizer(), new ArrayDenormalizer()],
            [new XmlEncoder()]
        );

        $users = $serializer->deserialize($data, 'App\Entity\User[]', 'xml', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['roles']]);

        foreach ($users as $user) {
            $existingUser = $userRepository->findOneBy(['email' => $user->getEmail()]);

            if($existingUser === null) {
                $user->setRoles(['ROLE_USER']);
                usleep(1000000);

                $manager->persist($user);
                $manager->flush();
                $manager->clear();
            }
        }

        return new Response();
    }

    public function computeProgressbarPercentage(UserRepository $userRepository)
    {
        $count = $userRepository->countAll();
        $nbUsersToImport = 11;

        return new Response(floor(($count / $nbUsersToImport) * 100));
    }
}
