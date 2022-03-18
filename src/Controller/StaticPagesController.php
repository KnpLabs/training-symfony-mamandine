<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StaticPagesController extends AbstractController
{
    public function cookies()
    {
        return $this->render('static/cookies.html.twig', []);
    }
}
