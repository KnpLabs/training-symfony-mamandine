<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CakeController extends AbstractController
{
    public function list()
    {
        $pdo = new \PDO($this->getParameter('pdo_dsn'));

        $cakes = $pdo
            ->query('SELECT * FROM cake ORDER BY created_at DESC')
            ->fetchAll()
        ;

        dump($this->getParameter('environment'));

        return $this->render('cake/list.html.twig', [
            'cakes' => $cakes,
        ]);
    }
}
