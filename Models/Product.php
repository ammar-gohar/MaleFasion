<?php

class Product extends DBHandler
{

  public $id;
  public $name;
  public $category;
  public $description;
  public $variants;
  public $images;
  public $videos;
  public $reviews;
  public $created_at;
  public $updated_at;
  public $count;


  //============== POST ==============//
  protected function create_product(string $name, int $categoryId, string $description = null, int $colorId, int $sizeId, int $stock, float $price,)
  {
    try {
      $query = "INSERT INTO prducts(`name`, `category_id`, `description`,) 
      VALUES (?, ?, ?);";

      $parameters = [
        $name,
        $categoryId,
        $description,
      ];

      $this->connect();

      $stmt = $this->pdo->prepare($query);

      $stmt->execute($parameters);

      $lastId = $this->pdo->lastInsertId();

      $stmt->closeCursor();

      $query = "INSERT INTO product_variations(`product_id`, `color_id`, `size_id`, `price`, `stock`)
      VALUES (:id, :color, :size, :price, :stock) ";

      $stmt = $this->pdo->prepare($query);

      $stmt->bindParam(":id", $lastId, PDO::PARAM_INT);
      $stmt->bindParam(":color", $colorId, PDO::PARAM_INT);
      $stmt->bindParam(":size", $sizeId, PDO::PARAM_INT);
      $stmt->bindParam(":price", $price);
      $stmt->bindParam(":stock", $stock, PDO::PARAM_INT);

      $stmt->execute();

      $query = null;
      $stmt = null;
      $this->disconnect();
    } catch (PDOException $e) {
      print("Error: " . $e->getMessage());
      exit();
    }

  }

//============== GET ==============//
  protected function get_product(int $id)
  {
    
    try {
      $query = 
      "SELECT *
      FROM products
      WHERE `id` = ?;";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$id]);
      
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      $assests = $this->get_product_assets([$result['id']]);

      if($result){
        
        $this->images = $assests['images'];
        $this->videos = $assests['videos'];
        $this->fill_properties($result);
        $this->get_product_variants($id);
        
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

  protected function get_product_variants(int $id)
  {
    
    try {
      $query = 
      "SELECT a.`id`, `size`, c.`name` as color, price, sale_price, stock, created_at,        updated_at
      FROM (
        SELECT p.`id`, product_id, s.`name` as `size`, color_id, price, sale_price, stock, created_at, updated_at
        FROM product_variations p
        JOIN sizes s
        ON s.`id` = p.`size_id`) a
      JOIN colors c
      ON a.`color_id` = c.`id`
      WHERE `product_id` = ?";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$id]);
      
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($result as $key => $var) {
        $result[$key] = (object) $var;
      };

      if($result){
        
        $this->variants = (object) $result;

        $query = null;
        $stmt = null;
        $result = null;
        $this->disconnect();
        return true;
      };

    } catch (PDOException $e) {
      print("Error: " . $e->getmessage());
      exit();
    }
    
  }

  protected function get_products(string $cond = "1", int $limit = 20, int $page = 1, string $order = '`name` ASC')
  {
    try {

      $offset = ( $page - 1 ) * $limit;

      $query = 
      "SELECT *
      FROM products
      WHERE " . $cond . "
      ORDER BY " . $order . 
      " LIMIT :limit OFFSET :offset; ";

      $countQuery = 
      "SELECT COUNT(*)
      FROM products
      WHERE " . $cond . "
      ORDER BY " . $order . 
      " LIMIT :limit OFFSET :offset; ";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
      $stmt->execute();
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      $this->pdo->prepare($query)->closeCursor();
      
      $countStmt = $this->pdo->prepare($countQuery);
      $countStmt->bindParam(":limit", $limit, PDO::PARAM_INT);
      $countStmt->bindParam(":offset", $offset, PDO::PARAM_INT);
      $countStmt->execute();
      $this->count = $countStmt->fetchColumn();
      
      
      if($products){
        
        foreach($products as $product){
          $categoryIds[] = $product["category_id"];
        }
        foreach($products as $product){
          $assetsIds[] = $product["id"];
        }

        $categories = $this->get_product_category($categoryIds);
        $assets = $this->get_product_assets($assetsIds);

        foreach ($products as $k => $product) {
          foreach ($categories as $category){
            if ($category->id === $product["category_id"]){
              $product["category"] = $category;
            };
          };
          foreach($assets['images'] as $image){
            if ($image['product_id'] === $product['id']){
              $product["images"][] = (object) $image;
            };
          }
          $product["images"] ??= null;
          $products[$k] = (object) $product;
        };

        $query = null;
        $stmt = null;
        
        $this->disconnect();
        return (object) $products;
      } else {

        $query = null;
        $stmt = null;
        
        $this->disconnect();
        return $products;
      };
    } catch (PDOException $e) {
      print("Error: " . $e->getmessage());
      exit();
    }
  }

  private function get_product_assets(array $ids){
    try {
      $query = 
      "SELECT *
      FROM product_images
      WHERE product_id IN (? ";

      for($i = 1; $i < count($ids);  $i++){
        $query .= ", ?";
      };

      $query .= ')';

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute($ids);
      
      $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $query2 = 
      "SELECT *
      FROM product_videos
      WHERE product_id IN (?";
      
      $stmt->closeCursor();

      for($i = 1; $i < count($ids);  $i++){
        $query2 .= ", ?";
      };

      $query2 .= ')';
      
      $stmt = $this->pdo->prepare($query2);
      
      $stmt->execute($ids);
      
      $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return ['images' => $images, 'videos' => $videos];
    
    } catch (PDOException $e) {
      print("Error: " . $e->getmessage());
      exit();
    }
  }

  protected function get_one_variant(int $id)
  {
    try {
      $query = 
      "SELECT a.`id` as id, product_id, name, description, `size`, color, price, sale_price, stock
      FROM products
      JOIN (
        SELECT a.`id`, product_id, `size`, c.`name` as color, price, sale_price, stock, created_at, updated_at
        FROM (
          SELECT p.`id`, product_id, s.`name` as `size`, color_id, price, sale_price, stock, created_at, updated_at
          FROM product_variations p
          JOIN sizes s
          ON s.`id` = p.`size_id`) a
        JOIN colors c
        ON a.`color_id` = c.`id`) a
      ON products.id = a.product_id
      WHERE a.id = ?";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$id]);
      
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result;

    } catch (PDOException $e) {
      print("Error: " . $e->getmessage());
      exit();
    }
  }
  
  //============== UPDATE/PATCH ==============//
  protected function update_product(array $params)
  {
    $query = "UPDATE products SET ";
    
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

  protected function update_variant(int $variantId, array $params)
  {
    $query = "UPDATE product_variations SET ";
    
    foreach($params as $key => $param){
      $toUpdate[] = "`$key` = ? ";
      $values[] = $param;
    };

    $query .= implode(",", $toUpdate);

    $query .= "WHERE `id` = ?";

    $this->connect();

    $values[] = $variantId;

    $stmt = $this->pdo->prepare($query);

    $stmt->execute($values);

    $query = null;
    $stmt = null;
    $this->disconnect();

  }
  
  //============== DELETE ==============//
  protected function delete_product()
  {
    $query = "DELETE FROM products WHERE id = ?";

    $this->connect();
    
    $stmt = $this->pdo->prepare($query);
    
    $stmt->execute([$this->id]);
    
    $query = null;
    $stmt = null;
    $this->disconnect();
  
  }

  //============== OTHERS ==============//

  protected function fill_properties(array $result){
    $this->id = $result["id"];
    $this->name = $result["name"];
    $this->category = $this->get_product_category([$result["category_id"]]);
    $this->description = $result["description"];
    $this->created_at = $result["created_at"];
    $this->updated_at = $result["updated_at"];
  }

  protected function get_product_category(array $ids)
  {
    $category = new Category();
    $cats = $category->get_prod_category($ids);
    return count($cats) == 1 ? $cats[0] : $cats;
  }

  protected function get_product_reviews(int $id)
  {
    $review = new Review();
    return $review->get_reviews($this->id);
  }

}
