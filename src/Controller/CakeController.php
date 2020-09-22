<?php

namespace App\Controller;

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
        if ('POST' === $request->getMethod()) {
            $name =  $request->get('name');
            $description =  $request->get('description');
            $price =  $request->get('price');
            $image =  $request->get('image');
            $createdAt = (new \DateTime())->format(\DateTime::RFC3339);

            $this->getPdo()->query('INSERT INTO cake(`name`, `description`, `price`, `created_at`, `image`) VALUES ("'.$name.'", "'.$description.'", "'.$price.'", "'.$createdAt.'", "'.$image.'");');

            $this->addFlash('success', 'The cake has been created');

            return $this->redirectToRoute('app_cake_list');
        }

        return $this->render('cake/create.html.twig');
    }

    private function getPdo()
    {
        if (null === $this->pdo) {
            $this->pdo = new \PDO($this->getParameter('pdo_dsn'));
        }

        return $this->pdo;
    }
}
