<?php

namespace App\Controller;

use App\FormType\OrderType;
use App\Repository\OrderRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\WorkflowInterface;

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

    public function list(OrderRepository $orderRepository)
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $orders = $orderRepository->findAll();
        } else {
            $orders = $user->getOrders();
        }

        return $this->render('order/list.html.twig', [
            'orders' => $orders,
        ]);
    }

    public function cancel(WorkflowInterface $orderValidationWorkflow, OrderRepository $orderRepository, $orderId)
    {
        $user = $this->getUser();

        $order = $orderRepository->find($orderId);

        if($order->getBuyer() !== $user && !$orderValidationWorkflow->can($order, 'cancel')) {
            $this->addFlash('danger', 'This order can\'t be cancelled');
        } else {
            $this->addFlash('success', 'The order has been cancelled');
            $order->setValidationStatus("cancel");
            $orderValidationWorkflow->apply($order, 'cancel');

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('order_list');
    }
}
