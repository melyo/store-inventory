<?php

namespace App;

use App\Interfaces\OrderProcessorInterface;
use App\Products;
use App\Collection;

class OrderProcessor implements OrderProcessorInterface {

    /**
     * Collection of products
     *
     * @var Collection
     */
    private $collection;

    /**
     * Constructor method
     * 
     * @param Collection $collection
     */
    function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Process JSON File
     * 
     * @param string $filePath
     * @return void
     */
    public function processFromJson(string $filePath): void
    {
        $json = file_get_contents($filePath);

        $orders = json_decode($json, true);

        foreach ($orders as $date => $dailyOrders) {
            $this->processDailyOrders($dailyOrders, $date);
        }
    }

    /**
     * Display result on terminal
     * 
     * @return void
     */
    public function displayResult(): void
    {
        foreach ($this->collection->getKeys() as $productId) {
            echo "$productId - ".$this->collection->getName($productId)."\n";
            echo "Sold Total: ".$this->collection->getSoldTotal($productId)."\n";
            echo "Purchased Received: ".$this->collection->getPurchasedReceivedTotal($productId)."\n";
            echo "Purchased Pending: ".$this->collection->getPurchasedPendingTotal($productId)."\n";
            echo "Stock Level: ".$this->collection->getStockLevel($productId)."\n\n";
        }
    }

    /**
     * Process Daily Orders
     * 
     * @param array $dailyOrders
     * @param int $date
     * @return void
     */
    private function processDailyOrders (array $dailyOrders, int $date): void
    {

        # Receive stock for any pending purchase orders made 2 days prior
        $this->receiveOrder($date);

        # Process all orders for that day
        $this->processOrder($dailyOrders);

        # Place purchase order for all products low in stock (excluding items in a pending purchase order)
        $this->placeOrder($date);

    }

    /**
     * Process an order
     * 
     * @param array $dailyOrders
     * @return void
     */
    private function processOrder (array $dailyOrders): void
    {
        foreach ($dailyOrders as $order) {
            # If any orders cannot be fulfilled because there is no stock, the whole order shall be rejected.
            if(!$this->isAvailable($order)) {
                continue;
            }

            foreach ($order as $productId => $quantity) {
                $this->collection->getProduct($productId)->sell($quantity);
            }
        }
    }

    /**
     * Receive purchased order
     * 
     * @param int $date
     * @return void
     */
    private function receiveOrder (int $date): void
    {
        foreach ($this->collection->all() as $product) {
            if ($product->pending != 0 && ($date - $product->datePurchased) == 2) {
                $product->receiveOrder($date);
            }
        }
    }

    /**
     * Place purchase order
     * 
     * @param int $date
     * @return void
     */
    private function placeOrder (int $date): void
    {
        foreach ($this->collection->all() as $product) {
            if ($product->pending == 0 && $product->stock < 10) {
                $product->placeOrder($date);
            }
        }
    }

    /**
     * Check the availability in a single order
     * 
     * @param array $order
     * @return bool
     */
    private function isAvailable (array $order): bool
    {
        $products = array_keys($order);

        foreach ($products as $productId) {
            $stock = $this->collection->getStockLevel($productId);
            if ($stock < $order[$productId]) {
                return false;
            }
        }

        return true;
    }

}
