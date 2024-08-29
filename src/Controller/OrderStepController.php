<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderStep;
use App\Repository\OrderRepository;
use App\Repository\OrderStepRepository;
use App\Repository\ProductRepository;
use App\Repository\StatusRepository;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderStepController extends AbstractController
{
    public function __construct(
        private OrderStepRepository $orderStepRepository,
        private OrderRepository $orderRepository,
        private EntityManagerInterface $entityManager,
        private StatusRepository $statusRepository,
        private ProductRepository $productRepository,
        private UnitRepository $unitRepository
    ) {}

    #[Route('/api/orders/{id}/order_steps', name: 'app_order_step', methods: ['POST'])]
    public function createOrderStep(int $id, Request $request): JsonResponse
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Validate and assign values to the new OrderStep
        $orderStep = new OrderStep();
        $orderStep->setOrder($order);
        $orderStep->setType($data['type'] ?? null);
        $orderStep->setDescription($data['description'] ?? null);
        $orderStep->setScheduledArrival(new \DateTimeImmutable($data['scheduledArrival']));
        $orderStep->setScheduledDeparture(new \DateTimeImmutable($data['scheduledDeparture']));
        $orderStep->setQuantity($data['quantity'] ?? null);
        $orderStep->setPosition($data['position'] ?? null);

        // Assuming address is an array, create a new Address entity and assign it
        $addressData = $data['address'] ?? null;
        if ($addressData) {
            $address = new Address();
            $address->setStreet($addressData['street'] ?? null);
            $address->setCity($addressData['city'] ?? null);
            $address->setZipCode($addressData['zipCode'] ?? null);
            $address->setStateProvince($addressData['stateProvince'] ?? null);
            $address->setCountry($addressData['country'] ?? null);
            $address->setLatitude($addressData['latitude'] ?? null);
            $address->setLongitude($addressData['longitude'] ?? null);
            $orderStep->setAddress($address);
            $this->entityManager->persist($address);
        }

        // Extract the ID from the URL
        $statusId = (int) basename($data['status']);
        $productId = (int) basename($data['product']);
        $unitId = (int) basename($data['unit']);

        // Retrieve the entities using the extracted IDs
        $status = $this->statusRepository->find($statusId);
        $product = $this->productRepository->find($productId);
        $unit = $this->unitRepository->find($unitId);

        if (!$status || !$product || !$unit) {
            return new JsonResponse(['error' => 'Related entity not found'], 404);
        }

        $orderStep->setStatus($status);
        $orderStep->setProduct($product);
        $orderStep->setUnit($unit);

        // Save the new OrderStep
        $this->entityManager->persist($orderStep);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'OrderStep created successfully'], 201);
    }

    #[Route('/api/order_steps/{id}', name: 'app_order_step_update', methods: ['PATCH'])]
    public function updateOrderStep(int $id, Request $request): JsonResponse
    {
        // Récupérer l'OrderStep existant
        $orderStep = $this->orderStepRepository->find($id);
        if (!$orderStep) {
            return new JsonResponse(['error' => 'OrderStep not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Mettre à jour les champs si les données sont présentes
        if (isset($data['type'])) {
            $orderStep->setType($data['type']);
        }
        if (isset($data['description'])) {
            $orderStep->setDescription($data['description']);
        }
        if (isset($data['scheduledArrival'])) {
            $orderStep->setScheduledArrival(new \DateTimeImmutable($data['scheduledArrival']));
        }
        if (isset($data['scheduledDeparture'])) {
            $orderStep->setScheduledDeparture(new \DateTimeImmutable($data['scheduledDeparture']));
        }
        if (isset($data['quantity'])) {
            $orderStep->setQuantity($data['quantity']);
        }
        if (isset($data['position'])) {
            $orderStep->setPosition($data['position']);
        }

        // Mettre à jour l'adresse si elle est présente dans la requête
        if (isset($data['address'])) {
            $addressData = $data['address'];
            $address = $orderStep->getAddress() ?: new Address();
            $address->setStreet($addressData['street'] ?? $address->getStreet());
            $address->setCity($addressData['city'] ?? $address->getCity());
            $address->setZipCode($addressData['zipCode'] ?? $address->getZipCode());
            $address->setStateProvince($addressData['stateProvince'] ?? $address->getStateProvince());
            $address->setCountry($addressData['country'] ?? $address->getCountry());
            $address->setLatitude($addressData['latitude'] ?? $address->getLatitude());
            $address->setLongitude($addressData['longitude'] ?? $address->getLongitude());
            $orderStep->setAddress($address);
            $this->entityManager->persist($address);
        }

        // Mettre à jour le statut, le produit et l'unité si présents
        if (isset($data['status'])) {
            $statusId = (int) basename($data['status']);
            $status = $this->statusRepository->find($statusId);
            if ($status) {
                $orderStep->setStatus($status);
            } else {
                return new JsonResponse(['error' => 'Status not found'], 404);
            }
        }

        if (isset($data['product'])) {
            $productId = (int) basename($data['product']);
            $product = $this->productRepository->find($productId);
            if ($product) {
                $orderStep->setProduct($product);
            } else {
                return new JsonResponse(['error' => 'Product not found'], 404);
            }
        }

        if (isset($data['unit'])) {
            $unitId = (int) basename($data['unit']);
            $unit = $this->unitRepository->find($unitId);
            if ($unit) {
                $orderStep->setUnit($unit);
            } else {
                return new JsonResponse(['error' => 'Unit not found'], 404);
            }
        }

        // Enregistrer les modifications
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'OrderStep updated successfully'], 200);
    }
}
