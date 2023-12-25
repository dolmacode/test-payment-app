<?php

namespace App\Controller\Api;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $products = $entityManager->getRepository(Product::class)->findAll();

        $results = [];
        foreach ($products as $product) {
            $results[] = $product->toArray();
        }

        return $this->json($results, 200);
    }

    public function show(EntityManagerInterface $entityManager, int $product_id): JsonResponse
    {
        return $this->json([
            'data' => $entityManager->getRepository(Product::class)->find($product_id)->toArray(),
        ], 200);
    }
}
