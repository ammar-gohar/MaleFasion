<?php

class Cart extends DBHandler
{

  public $id;
  public $products;

  //============== POST ==============//
  protected function create_cart(int $userId,)
  {
    try {
      $query = "INSERT INTO cart(`user_id`) 
      VALUES (?);";

      $this->connect();

      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$userId]);

      $query = null;
      $stmt = null;

      $this->get_cart($userId);

      $this->disconnect();

    } catch (PDOException $e) {
      print("Error: " . $e->getMessage());
      exit();
    }
  }

  protected function create_cart_product(int $productId, int $quantity, float $price)
  {
    try {
      $query = "INSERT INTO cart_products(`cart_id`, `product_id`, `quantity`, `price`) 
      VALUES (?, ?, ?, ?);";

      $this->connect();

      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$this->id, $productId, $quantity, $price]);

      $query = null;
      $stmt = null;

      $this->disconnect();

      return true;

    } catch (PDOException $e) {
      print("Error: " . $e->getMessage());
      exit();
    }
  }

  //============== GET ==============//
  protected function get_cart(int $userId)
  {
    try {
      $query = 
      "SELECT *
      FROM cart
      WHERE `user_id` = ?;";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$userId]);
      
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      $query = null;
      $stmt = null;

      if($result){
        $this->id = $result['id'];
        $this->get_cart_products($this->id);
      }

      return $result;

    } catch (PDOException $e) {
      print("Error: " . $e->getmessage());
      exit();
    }
  }

  protected function get_cart_products(int $cartId)
  {
    try {
      $query = 
      "SELECT cart_products.id as product_cart_id, cart_id, b.id as variant_id, b.product_id, name, description, `size`, color, b.price, sale_price, quantity, cart_products.price as total_price, default_image, stock, cart_products.created_at
      FROM cart_products
      JOIN (
          SELECT a.`id` as id, default_image, product_id, name, description, `size`, color, price, stock, sale_price
            FROM products
            JOIN (
              SELECT a.`id`, product_id, `size`, c.`name` as color, price, sale_price, stock
              FROM (
                SELECT p.`id`, product_id, s.`name` as `size`, stock, color_id, price, sale_price
                FROM product_variations p
                JOIN sizes s
                ON s.`id` = p.`size_id`) a
              JOIN colors c
              ON a.`color_id` = c.`id`) a
            ON products.id = a.product_id) b
      ON cart_products.product_id = b.id
      WHERE `cart_id` = ? AND `is_ordered` = 0
      ORDER BY cart_products.created_at DESC";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$cartId]);
      
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $result;

    } catch (PDOException $e) {
      print("Error: " . $e->getmessage());
      exit();
    }
  }

  protected function get_total_price()
  {
    try {
      $query = 
      "SELECT SUM(price)
      FROM cart_products
      WHERE `cart_id` = ? AND `is_ordered` = 0";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$this->id]);
      
      $result = $stmt->fetchColumn();

      return $result;

    } catch (PDOException $e) {
      print("Error: " . $e->getmessage());
      exit();
    }
  }

  //============== DELETE ==============//
  protected function delete_cart_product(int $cartProductId, int $cartId, int $productId)
  {
    $query = "DELETE FROM cart_products 
    WHERE `id` = ? AND `cart_id` = ? AND `product_id` = ?";

    $this->connect();

    $stmt = $this->pdo->prepare($query);

    $stmt->execute([$cartProductId, $cartId, $productId]);

    $query = null;
    $stmt = null;
    $this->disconnect();

    return true;
  }

  protected function update_cart_products(array $productCartIds, array $params)
  {
    $query = "UPDATE cart_products SET ";
    
    foreach($params as $key => $param){
      $toUpdate[] = "`$key` = ? ";
      $values[] = $param;
    };

    $query .= implode(",", $toUpdate);

    $query .= "WHERE `id` IN (?";

    for ($i = 1; $i < count($productCartIds); $i ++){
      $query .= ", ?";
    }

    $query .= ")";

    $this->connect();

    $stmt = $this->pdo->prepare($query);
    
    $stmt->execute(array_merge($values, $productCartIds));

    $query = null;
    $stmt = null;
  }

}