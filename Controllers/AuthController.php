<?php

class AuthController extends User
{

  private $errors = [];
  private $oldInputs = [];

  public function login(string $input, string $pswrd)
  {

    if(empty($input) || empty($pswrd)){
      $this->errors["empty_username"] = "Please fill all fields.";
      return false;
    }

    if($this->invalid_email($input)){
      unset($this->errors["invalid_email"]);
      
      if(!$this->invalid_username($input)){

        if(!$this->get_user(["username" => $input])){
          $this->errors["no_username"] = "Your username doesn't exist.";
          exit();
        } else {
          return true;
        }

      };

    } else {

      if(!$this->get_user(["email" => $input])){
        $this->errors["no_email"] = "Your email doesn't exist.";
        exit();
      } else {
        return true;
      }

    };

  }

  public function signup(string $first_name, string $last_name, string $username, string $email, string $gender, string $birth_date, string $pswrd, string $repswrd, string $terms)
  {

    
    $this->oldInputs = [
      "first_name" => $first_name,
      "last_name" => $last_name,
      "username" => $username,
      "gender" => $gender,
      "email" => $email,
      "birth_date" => $birth_date,
    ];

    if($terms !== "true"){
      $this->errors["terms"] = "You need to agree to our terms";
      $this->assign_errors();
      return false;
    }

    if($this->empty_input($first_name, $last_name, $username, $email, $gender, $birth_date, $pswrd, $repswrd)){
      $this->assign_errors();
      return false;
    }

    $this->invalid_name($first_name, $last_name);

    $this->invalid_username($username);
    
    $this->invalid_email($email);

    $this->bdate_validation($birth_date);

    if(!$this->invalid_username($username)){
      $this->username_exits($username);
    };

    if(!$this->invalid_email($email)){
      $this->email_exits($email);
    };

    $this->password_confirmation($pswrd, $repswrd);

    if($this->errors){
      $this->assign_errors();
      return false;
    } else {
      $this->create_user($first_name, $last_name, $username, $email, $gender, $birth_date, $pswrd,);
      return true;
    }

  }

  private function empty_input(string $first_name, string $last_name, string $username, string $email, string $gender, string $birth_date, string $pswrd, string $repswrd)
  {
    if(empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($gender) || empty($birth_date) || empty($pswrd) || empty($repswrd)){
      $this->errors["empty_input"] = "Please fill in all the fields.";
    }
  }

  private function invalid_name(string $first_name, string $last_name)
  {
    if(preg_match( "/[^a-zA-z]/", trim($first_name)) || preg_match( "/[^a-zA-z]/", trim($last_name))){
      $this->errors["invalid_name"] = '"First name" & "Last name" fields only accepts letters.';
    }
  }

  private function invalid_email(string $email)
  {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $this->errors["invalid_email"] = "Please insert a valid email.";
      return true;
    } else {
      return false;
    }
  }

  private function invalid_username(string $username)
  {
    if(preg_match( "/[^a-zA-z0-9.-_]/", trim($username))){
      $this->errors["invalid_username"] = 'Please insert a valid username. Only letters, numbers, and "." "-" "_" are valid';
      return true;
    } else {
      return false;
    }
  }

  private function bdate_validation(string $date)
  {
    if(!strtotime($date) || strtotime($date) > time() || strtotime($date) < time()-31536000*150){
      $this->errors["invalid_bDate"] = "Please insert a valid birth date.";
    }
  }

  private function username_exits(string $username)
  {
    if($this->get_user(["username" => $username])){
      $this->errors["existed_username"] = "This username is already exists.";
    }
  }

  private function email_exits(string $email)
  {
    if($this->get_user(["email" => $email])){
      $this->errors["existed_email"] = "This email is already exists.";
    }
  }
  
  private function password_confirmation(string $pswrd, $repswrd)
  {
    if($pswrd !== $repswrd){
      $this->errors["pssword_not_confirmed"] = "Password and its confirmation is not the same.";
    };
  }

  private function assign_user_to_session()
  {
    $_SESSION["user"] = [
      "id" => $this->id,
      "first_name" => $this->first_name,
      "last_name" => $this->last_name,
      "username" => $this->username,
      "gender" => $this->gender,
      "email" => $this->email,
      "birth_date" => $this->birth_date,
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at,
    ];

  }

  public function assign_errors()
  {
    $_SESSION["signup_errors"] = $this->errors;
    $_SESSION["old_signup_inputs"] = $this->oldInputs;
  }

  public function logout()
  {
    session_destroy();
  }

}