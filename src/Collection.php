<?php

namespace App;

use App\Interfaces\InventoryInterface;
use App\Interfaces\ProductsSoldInterface;
use App\Interfaces\ProductsPurchasedInterface;
use App\Product;

class Collection implements InventoryInterface, ProductsSoldInterface, ProductsPurchasedInterface
{

    /**
     * Collection of products
     *
     * @var array
     */
    private $products = array();

    /**
     * Add a product object to collection
     * 
     * @param Product $product
     * @param int $productId
     * @return void
     */
    public function addProduct(Product $product, int $productId = null): void
    {
        if ($productId == null) {
            $this->products[] = $product;
        }
        else {
            if (isset($this->products[$productId])) {
                throw new \Exception("Product ID ($productId) already in use.");
            }
            else {
                $this->products[$productId] = $product;
            }
        }
    }

    /**
     * Get a product by ID
     * 
     * @param int $productId
     * @return Product
     */
    public function getProduct($productId): Product
    {
        if (isset($this->products[$productId])) {
            return $this->products[$productId];
        } else {
            throw new \Exception("Invalid product ID ($productId).");
        }
    }

    /**
     * Get all products
     * 
     * @return array
     */
    public function all(): array
    {
        return $this->products;
    }

    /**
     * Get all product IDs
     * 
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->products);
    }

    /**
     * Get remaining product stock
     * 
     * @param int $productId
     * @return int
     */
    public function getStockLevel(int $productId): int
    {
        return $this->getProduct($productId)->stock;
    }

    /**
     * Get total sold products
     * 
     * @param int $productId
     * @return int
     */
    public function getSoldTotal(int $productId): int
    {
        return $this->getProduct($productId)->sold;
    }

    /**
     * Get total purchased products
     * 
     * @param int $productId
     * @return int
     */
    public function getPurchasedReceivedTotal(int $productId): int
    {
        return $this->getProduct($productId)->received;
    }

    /**
     * Get total pending purchased order
     * 
     * @param int $productId
     * @return int
     */
    public function getPurchasedPendingTotal(int $productId): int
    {
        return $this->getProduct($productId)->pending;
    }

    /**
     * Get product name
     * 
     * @param int $productId
     * @return string
     */
    public function getName(int $productId): string
    {
        return $this->getProduct($productId)->name;
    }

}
