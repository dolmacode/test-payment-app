<?php

use App\Controller\Api\CountryController;
use App\Controller\Api\OrderController;
use App\Controller\Api\CatalogController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes)
{
    $routes->add('api_calculate_price', '/calculate-price')
        ->controller([OrderController::class, 'calculate_price'])
        ->methods(['POST']);

    $routes->add('api_purchase', '/purchase')
        ->controller([OrderController::class, 'purchase'])
        ->methods(['POST']);

    $routes->add('api_countries_list', '/countries')
        ->controller([CountryController::class, 'index'])
        ->methods(['GET']);

    $routes->add('api_catalog', '/catalog')
        ->controller([CatalogController::class, 'index'])
        ->methods(['GET']);

    $routes->add('api_product', '/product/{product_id}')
        ->controller([CatalogController::class, 'show'])
        ->methods(['GET']);
};