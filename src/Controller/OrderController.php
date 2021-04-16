<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Form events approach
 *
 * Examples:
 *
 *      Create an order with an existing Customer:
 *
 *         {
 *              "item": "Shoes",
 *              "customer": 1
 *          }
 *
 *
 *      Create an order with a new Customer:
 *
 *          {
 *              "item": "Shoes",
 *              "customer": {
 *                  "name": "Bob"
 *              }
 *          }
 *
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(OrderType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            return $this->json(['errors' => $this->get('serializer')->normalize($form->getErrors(true, false))], Reponse::HTTP_BAD_REQUEST);
        }

        $order = $form->getData();

        return $this->json([
            'order' => $order,
        ]);
    }
}
