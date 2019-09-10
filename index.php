<?php

require 'vendor/autoload.php';

use App\OrderProcessor;
use App\Products;
use App\Product;
use App\Collection;

# Create collection of objects
$products = new Products;
$collection = new Collection;
$collection->addProduct(new Product('Brownie'), $products::BROWNIE);
$collection->addProduct(new Product('Lamington'), $products::LAMINGTON);
$collection->addProduct(new Product('Blueberry Muffin'), $products::BLUEBERRY_MUFFIN);
$collection->addProduct(new Product('Croissant'), $products::CROISSANT);
$collection->addProduct(new Product('Chocolate Cake'), $products::CHOCOLATE_CAKE);

# Process Orders
$order = new OrderProcessor($collection);
$order->processFromJson('orders-sample.json');

# Display Result
$order->displayResult();
