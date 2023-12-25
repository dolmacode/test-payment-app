<?php

namespace App\Controller\Api;

use App\Entity\Country;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager) {
        $countries = $entityManager->getRepository(Country::class)->findAll();

        $results = [];
        foreach ($countries as $country) {
            $results[] = $country->toArray();
        }

        return $this->json($results, 200);
    }
}
