<?php

class Category extends DBHandler
{

  public $id;

  public $name;

  public function create_category(string $name)
  {
    try {
      $query = "INSERT INTO categories(`name`) 
      VALUES (?);";

      $parameters = [
        $name,
      ];

      $this->connect();

      $stmt = $this->pdo->prepare($query);

      $stmt->execute($parameters);

      $query = null;
      $stmt = null;
      $this->disconnect();
    } catch (PDOException $e) {
      print("Error: " . $e->getMessage());
      exit();
    }

  }

//============== GET ==============//

  protected function get_category(array $cond)
  {
    try {
      $query = "SELECT *
      FROM categories
      WHERE `" . array_key_first($cond) . "` = '" . $cond[array_key_first($cond)] ."';";
      
      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute();
      
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if($result){

        $this->id = $result["id"];
        $this->name = $result["name"];

        $query = null;
        $stmt = null;
        $this->disconnect();
        return $result;
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

  public function get_prod_category(array $ids)
  {
    
    try {
      $query = "SELECT *
      FROM categories
      WHERE `id` IN (?";

      for ($i=1; $i < count($ids); $i++) { 
        $query .= ", ?";
      };

      $query .= ");";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute($ids);
      
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      if($result){

        foreach ($result as $key => $value) {
          $result[$key] = (object) $value;
        }

        $query = null;
        $stmt = null;
        $this->disconnect();
        return $result;
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

  protected function get_categories()
  {
    try {
      $query = "SELECT *
      FROM categories;";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute();
      
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      if($result){
        
        foreach ($result as $key => $value) {
          $result[$key] = (object) $value;
        }

        $query = null;
        $stmt = null;
        
        $this->disconnect();
        return (object) $result;
      } else {

        $query = null;
        $stmt = null;
        
        $this->disconnect();
        return $result;
      };
    } catch (PDOException $e) {
      print("Error: " . $e->getmessage());
      exit();
    }
  }
  
  //============== UPDATE/PATCH ==============//
  protected function update_products(array $params)
  {
    $query = "UPDATE categories SET ";
    
    foreach($params as $key => $param){
      $toUpdate[] = "`$key` = ? ";
      $values[] = $param;
    };

    $query = $query . implode(",", $toUpdate);

    $query .= "WHERE `id` = ?";

    $this->connect();

    $values[] = $this->id;

    $stmt = $this->pdo->prepare($query);

    $stmt->execute($values);

    $query = null;
    $stmt = null;
    $this->disconnect();

  }
  
  //============== DELETE ==============//
  protected function delete_product()
  {
    $query = "DELETE FROM categories WHERE id = ?";

    $this->connect();
    
    $stmt = $this->pdo->prepare($query);
    
    $stmt->execute([$this->id]);
    
    $query = null;
    $stmt = null;
    $this->disconnect();
  }


}