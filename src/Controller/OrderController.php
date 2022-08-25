<?php

namespace App\Controller;

use App\FormType\OrderType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{
    public function create(Request $request)
    {
        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $order = $form->getData();
            $order->setValidationStatus("in_review");
            $order->setBuyer($this->getUser());
            $order->setCreationDate(new DateTime());
            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'The order has been created');

            return $this->redirectToRoute('cake_list');
        }

        return $this->render('order/create.html.twig', ['form' => $form->createView()],);
    }
}
