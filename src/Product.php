<?php

namespace App;

class Product {

    /**
     * Product name
     *
     * @var string
     */
    public $name;

    /**
     * Product stock level
     *
     * @var int
     */
    public $stock = 20;

    /**
     * Total product sold
     *
     * @var int
     */
    public $sold = 0;

    /**
     * Pending purchased order
     *
     * @var int
     */
    public $pending = 0;

    /**
     * Received product order
     *
     * @var int
     */
    public $received = 0;

    /**
     * Date purchased
     *
     * @var int
     */
    public $datePurchased = 0;

    /**
     * Constructor method
     * 
     * @param string $name
     */
    function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Receive purchased order
     * 
     * @param int $date
     * @return void
     */
    public function receiveOrder(int $date): void
    {
        $this->stock += 20;
        $this->received += 20;
        $this->pending = 0;
        $this->datePurchased = 0;
    }

    /**
     * Sell product operations
     * 
     * @param int $quantity
     * @return void
     */
    public function sell(int $quantity): void
    {
        $this->sold += $quantity;
        $this->stock -= $quantity;
    }

    /**
     * Place purchase order
     * 
     * @param int $date
     * @return void
     */
    public function placeOrder(int $date):void
    {
        $this->pending = 20;
        $this->datePurchased = $date;
    }

}
