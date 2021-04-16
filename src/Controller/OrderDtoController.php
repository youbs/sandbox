<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Order;
use App\Form\CreateOrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DTO approach

 * Create an Order with an existing Customer :
 *
 *      {
 *          "item": "plop",
 *          "customerId": 1
 *      }
 *
 * Create an Order with a new Customer :
 *
 *      {
 *          "item": "plop",
 *          "customerName": "Bob"
 *      }
 *
 */
class OrderDtoController extends AbstractController
{
    /**
     * @Route("/dto_order", name="order_dto_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(CreateOrderType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            return new JsonResponse('invalid form', Response::HTTP_BAD_REQUEST);
        }

        $createOrder = $form->getData();

        if (isset($createOrder->customerId)) {
            $customer = $this->getDoctrine()->getRepository(Customer::class)->find($createOrder->customerId);
        } else {
            $customer = new Customer();
            $customer->setName($createOrder->customerName);
        }

        $order = new Order();
        $order->setItem($createOrder->item);
        $order->setCustomer($customer);

        // Persist $order;

        return $this->json([
            'order' => $order,
        ]);
    }
}
