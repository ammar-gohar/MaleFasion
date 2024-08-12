<?php

class User extends DBHandler
{

  public $id;
  public $first_name;
  public $last_name;
  public $username;
  public $email;
  public $gender;
  public $birth_date;
  public $pic;
  private $pswrd;
  public $created_at;
  public $updated_at;


  //============== POST ==============//
  protected function create_user(string $first_name, string $last_name, string $username, string $email, string $gender, string $birth_date, string $pswrd)
  {
    try {
      $query = "INSERT INTO users(`first_name`, `last_name`, `gender`, `birth_date`, `username`, `email`, `pswrd`) 
      VALUES (?, ?, ?, ?, ?, ?, ?);";

      $hashedPswrd = password_hash($pswrd, PASSWORD_BCRYPT, ['cost' => 12]);

      $parameters = [
        $first_name,
        $last_name,
        $gender,
        $birth_date,
        $username,
        $email,
        $hashedPswrd,
      ];

      $this->connect();

      $stmt = $this->pdo->prepare($query);

      $stmt->execute($parameters);

      $query = null;
      $stmt = null;

      $this->get_user(["username" => $parameters[4]]);

      $this->disconnect();
    } catch (PDOException $e) {
      print("Error: " . $e->getMessage());
      exit();
    }

  }

//============== GET ==============//
  protected function get_user(array $params)
  {
    
    try {
      $query = "SELECT *
      FROM users
      WHERE `" . array_key_first($params) . "` = ?;";

      $this->connect();
      
      $stmt = $this->pdo->prepare($query);

      $stmt->execute([$params[array_key_first($params)]]);
      
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if($result){
        
        $this->fill_properties($result);
        
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

  //============== UPDATE/PATCH ==============//
  protected function update_user(array $params)
  {
    $query = "UPDATE users SET ";
    
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
  protected function delete_user()
  {
    $query = "DELETE FROM users WHERE id = ?";

    $this->connect();
    
    $stmt = $this->pdo->prepare($query);
    
    $stmt->execute([$this->id]);
    
    $query = null;
    $stmt = null;
    $this->disconnect();
  
  }

  //============== OTHERS ==============//
  protected function check_password(string $pswrd)
  {
    return password_verify($pswrd, $this->pswrd);
  }

  protected function fill_properties(array $result){
    $this->id = $result["id"];
    $this->pic = $result["pro_pic"];
    $this->first_name = $result["first_name"];
    $this->last_name = $result["last_name"];
    $this->username = $result["username"];
    $this->gender = $result["gender"];
    $this->email = $result["email"];
    $this->birth_date = $result["birth_date"];
    $this->pswrd = $result["pswrd"];
    $this->created_at = $result["created_at"];
    $this->updated_at = $result["updated_at"];
  }

}
