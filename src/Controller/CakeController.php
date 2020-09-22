<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CakeController extends AbstractController
{
    public function list()
    {
        $pdo = new \PDO('sqlite:'. __DIR__ .'/../../var/cakes.sqlite');

        $cakes = $pdo
            ->query('SELECT * FROM cake ORDER BY created_at DESC')
            ->fetchAll()
        ;

        return $this->render('cake/list.html.twig', [
            'cakes' => $cakes,
        ]);
    }
}
