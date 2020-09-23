<?php

namespace App\Controller;

use App\FormType\CakeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CakeController extends AbstractController
{
    private $pdo;

    public function list()
    {
        $cakes = $this
            ->getPdo()
            ->query('SELECT * FROM cake ORDER BY created_at DESC')
            ->fetchAll()
        ;

        return $this->render('cake/list.html.twig', [
            'cakes' => $cakes,
        ]);
    }

    public function show($cakeId)
    {
        $cake = $this
            ->getPdo()
            ->query(sprintf('SELECT * FROM cake WHERE id = %d', $cakeId))
            ->fetch()
        ;

        if (!$cake) {
            throw $this->createNotFoundException(sprintf('The cake with id "%s" was not found.', $cakeId));
        }

        $categories = $this
            ->getPdo()
            ->query(sprintf('
                SELECT * FROM category
                INNER JOIN cake_category ON category.id = cake_category.category_id
                WHERE cake_category.cake_id = %d
            ', $cakeId))
            ->fetchAll()
        ;

        return $this->render('cake/show.html.twig', [
            'cake' => $cake,
            'categories' => $categories,
        ]);
    }

    public function create(Request $request)
    {
        $form = $this->createForm(CakeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $name = $data['name'];
            $description = $data['description'];
            $price = $data['price'];
            $createdAt = new \DateTime();
            $image = $data['image'];

            $this->getPdo()->query('INSERT INTO cake(`name`, `description`, `price`, `image`) VALUES ("'.$name.'", "'.$description.'", "'.$price.'", "'.$image.'");');


            $this->addFlash('success', 'Cake created !');

            return $this->redirectToRoute('app_cake_list');
        }

        return $this->render('cake/create.html.twig', [
            'form'   => $form->createView(),
        ]);
    }

    private function getPdo()
    {
        if (null === $this->pdo) {
            $this->pdo = new \PDO($this->getParameter('pdo_dsn'));
        }

        return $this->pdo;
    }
}
