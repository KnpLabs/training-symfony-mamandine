<?php

namespace App\Controller;

use App\FormType\CakeType;
use App\Repository\CakeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CakeController extends AbstractController
{
    public function list(CakeRepository $cakeRepository)
    {
        return $this->render('cake/list.html.twig', [
            'cakes' => $cakeRepository->findAll(),
        ]);
    }

    public function search(Request $request, CakeRepository $cakeRepository)
    {
        $page = $request->query->get('page') ?? 1;
        $search = $request->query->get('q');

        $countCakes = $cakeRepository->countAll($search);

        $cakes = $cakeRepository->search(
            $search,
            $page
        );

        return $this->render('cake/search.html.twig', [
            'cakes' => $cakes,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $countCakes / 10
        ]);
    }

    public function show(CakeRepository $cakeRepository, $cakeId)
    {
        $cake = $cakeRepository->find($cakeId);

        if (!$cake) {
            throw $this->createNotFoundException(sprintf('The cake with id "%s" was not found.', $cakeId));
        }

        $categories = $cake->getCategories();

        return $this->render('cake/show.html.twig', [
            'cake'  => $cake,
            'categories' => $categories,
        ]);
    }

    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CakeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $cake = $form->getData();
            $em->persist($cake);
            $em->flush();

            $this->addFlash('success', 'The cake has been created');

            return $this->redirectToRoute('cake_list');
        }

        return $this->render('cake/create.html.twig', ['form' => $form->createView()],);
    }

    public function edit(Request $request, CakeRepository $cakeRepository, $cakeId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $cake = $cakeRepository->find($cakeId);

        if (!$cake) {
            throw $this->createNotFoundException(sprintf('The cake with id "%s" was not found.', $cakeId));
        }

        $form = $this->createForm(CakeType::class,$cake);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $cake = $form->getData();
            $em->flush();

            $this->addFlash('success', 'The cake has been created');

            return $this->redirectToRoute('cake_list');
        }

        return $this->render('cake/create.html.twig', ['form' => $form->createView()],);
    }

}
