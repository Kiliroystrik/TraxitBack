<?php

namespace App\State;

use App\Entity\Order;
use App\Entity\OrderStep;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderStepPostProcessor implements ProcessorInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        dd("ici");
        // Ensure that the data is an instance of OrderStep
        if (!$data instanceof OrderStep) {
            throw new \InvalidArgumentException('Expected instance of OrderStep');
        }

        // Retrieve the Order ID from the URI variables
        $orderId = $uriVariables['id'] ?? null;
        if (!$orderId) {
            throw new \InvalidArgumentException('Order ID is required');
        }

        // Find the corresponding Order entity
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);
        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        // Associate the Order with the OrderStep
        $data->setOrder($order);


        // Handle persistence (save the OrderStep)
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
