<?php

use PHPUnit\Framework\TestCase;

use App\OrderProcessor;
use App\Products;
use App\Product;
use App\Collection;

class SuccessOrderProcessTest extends TestCase {

    private $products;

    private $productIds;

    public function processOrder()
    {
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
        $order->processFromJson( getcwd() . '/orders-sample.json');

        $this->productIds = $products;
        $this->products = $order->result();
    }

    public function testInventory()
    {

        $this->processOrder();

        $this->assertSame(51, $this->products[$this->productIds::BROWNIE]->sold);
        $this->assertSame(20, $this->products[$this->productIds::BROWNIE]->pending);
        $this->assertSame(40, $this->products[$this->productIds::BROWNIE]->received);
        $this->assertSame(9, $this->products[$this->productIds::BROWNIE]->stock);

        $this->assertSame(38, $this->products[$this->productIds::LAMINGTON]->sold);
        $this->assertSame(20, $this->products[$this->productIds::LAMINGTON]->pending);
        $this->assertSame(20, $this->products[$this->productIds::LAMINGTON]->received);
        $this->assertSame(2, $this->products[$this->productIds::LAMINGTON]->stock);

        $this->assertSame(28, $this->products[$this->productIds::BLUEBERRY_MUFFIN]->sold);
        $this->assertSame(0, $this->products[$this->productIds::BLUEBERRY_MUFFIN]->pending);
        $this->assertSame(20, $this->products[$this->productIds::BLUEBERRY_MUFFIN]->received);
        $this->assertSame(12, $this->products[$this->productIds::BLUEBERRY_MUFFIN]->stock);

        $this->assertSame(24, $this->products[$this->productIds::CROISSANT]->sold);
        $this->assertSame(0, $this->products[$this->productIds::CROISSANT]->pending);
        $this->assertSame(20, $this->products[$this->productIds::CROISSANT]->received);
        $this->assertSame(16, $this->products[$this->productIds::CROISSANT]->stock);

        $this->assertSame(29, $this->products[$this->productIds::CHOCOLATE_CAKE]->sold);
        $this->assertSame(0, $this->products[$this->productIds::CHOCOLATE_CAKE]->pending);
        $this->assertSame(20, $this->products[$this->productIds::CHOCOLATE_CAKE]->received);
        $this->assertSame(11, $this->products[$this->productIds::CHOCOLATE_CAKE]->stock);

    }

}
