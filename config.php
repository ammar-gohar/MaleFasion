<?php 

define("_ROOT_", "/MaleFasion");

class DBHandler
{
  protected $host = 'localhost';
  protected $db_name = 'malefasion';
  protected $db_username = 'root';
  protected $db_password  = '';
  protected $pdo;

  protected function connect() {
    try {
      $pdo = new PDO("mysql:host=$this->host;dbname=$this->db_name;", $this->db_username, $this->db_password);
      $this->pdo = $pdo;
    } catch (PDOException $e) {
      print("Error: " . $e->getMessage() . "<br>");
      exit();
    }
  }

  protected function disconnect()
  {
    $this->pdo = null;
  }
};

ini_set("session.use_only_cookies", 1);
ini_set("session.use_strict_mode", 1);

session_set_cookie_params([
  "lifetime" => 1800,
  "path" => "/",
  "domain" => "localhost",
  "secure" => true,
  "httponly" => true,
]);

session_start();

session_regenerate_id(true);

if(!isset($_SESSION["regenerate_time"])){
  $_SESSION["regenerate_time"] = time();
} else if (time() - $_SESSION["regenerate_time"] >= 60 * 30) {
  session_regenerate_id(true);
  $_SESSION["regenerate_time"] = time();
};

function make_get_request(array $request)
{
  $reqs = array_merge($_GET, $request);
  
  foreach($reqs as $key => $req){
    $reqs[$key] = (string) $key . '=' . (string) $req;
  }
  
  return '?' . implode("&", $reqs);
};