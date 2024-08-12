<?php

class CartController extends Cart
{

  public function make_user_cart(int $userId)
  {
    $this->create_cart($userId);
  }

  public function get_user_cart(int $userId)
  {
    return $this->get_cart($userId) ? true : false;
  }

  public function add_to_cart(int $productId, int $quantity, float $price)
  {
    $this->create_cart_product($productId, $quantity, $price);
  }

  public function remove_product(int $productCartId, int $productId)
  {
    $this->delete_cart_product($productCartId, $this->id, $productId);
  }

  public function get_user_cart_products(int $userId)
  {

    $this->get_cart($userId);
    $result = $this->get_cart_products($this->id);
    return $result;

  }
  public function get_user_total_cost()
  {
    return $this->get_total_price();
  }

  public function assign_products_to_ordered(array $productCartIds)
  {
    $this->update_cart_products($productCartIds, ['is_ordered' => '1']);
  }

}