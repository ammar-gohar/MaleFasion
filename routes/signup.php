<?php
include_once "..\config.php";

if($_SERVER["REQUEST_METHOD"] !== "POST" || isset($_SESSION["user"])){
  header("location: ..\View\index.php");
  die();
};

require_once "..\Models\User.php";
require_once "..\Controllers\AuthController.php";

$errors = [];
$first_name = trim($_POST["first_name"]);
$last_name = trim($_POST["last_name"]);
$birth_date = $_POST["birth_date"];
$gender = $_POST["gender"];
$username = trim($_POST["username"]);
$email = trim($_POST["email"]);
$pswrd = $_POST["pswrd"];
$confirmation = $_POST["repswrd"];
$terms = $_POST["terms_agreement"];

$signup = new AuthController();

print_r($_POST);

if(!$signup->signup($first_name, $last_name, $username, $email, $gender, $birth_date, $pswrd, $confirmation, $terms)){
  header("location: ..\View\signup.php");
  exit();
};

$_SESSION["user"] = serialize($signup);
header("location:..\View\index.php");
exit();