<?php

class Order extends DBHandler
{

  protected $id;

  protected function create_order(int $userId, float $totalPrice)
  {
    try {
      $query = "INSERT INTO orders(`user_id`, `total`) 
      VALUES (?, ?);";

      $this->connect();

      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$userId, $totalPrice]);

      $query = null;
      $stmt = null;

      $this->id = $this->pdo->lastInsertId();

    } catch (PDOException $e) {
      print("Error: " . $e->getMessage());
      exit();
    }
  }

  protected function get_order(int $userId)
  {
    try {
      $query = "SELECT * FROM orders
      WHERE `user_id` = ? AND `status` NOT 'arrived';";

      $this->connect();

      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$userId]);

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      $query = null;
      $stmt = null;

      $this->id = $result['id'];

    } catch (PDOException $e) {
      print("Error: " . $e->getMessage());
      exit();
    }
  }

  protected function create_order_products(array $products)
  {
    try {
      $this->connect();
      $this->pdo->beginTransaction();

      foreach ($products as $value) {
        $query = "INSERT INTO orders_products(`order_id`, `product_id`, `qunatity`, `price`)
        VALUES ($this->id," . $value['variant_id'] . "," . $value['quantity'] . " , " . $value['price'];
        echo "<bre>";
        echo $query;
        continue;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
      }
      $this->pdo->commit();
      $this->disconnect();
      
    } catch(PDOException $e) {
      
      $this->pdo->rollback();
      echo "Error: " . $e->getMessage();
      $this->disconnect();
    }
    
  }

}