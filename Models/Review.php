<?php

class Review extends DBHandler
{

  public function get_reviews(int $id)
  {
    try {
      $query = "SELECT *
      FROM reviews
      WHERE `product_id` = ?;";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$id]);
      
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if($result){
        
        

        $query = null;
        $stmt = null;
        $result = null;
        $this->disconnect();
        return true;
      } else {

        $query = null;
        $stmt = null;
        $result = null;
        $this->disconnect();
        return false;
      };
    } catch (PDOException $e) {
      print("Error: " . $e->getmessage());
      exit();
    }
    
  }

}