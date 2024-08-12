<?php

class OrderController extends Order
{

  public function new_order(int $userId, float $total)
  {
    $this->create_order($userId, $total);
  }

  public function add_products(array $products)
  {
    $this->create_order_products($products);
  }
}