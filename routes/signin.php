<?php

include_once "../config.php";
if($_SERVER["REQUEST_METHOD"] !== "POST" || isset($_SESSION["user"])){
  header("location: ../View/index.php");
  die();
};

require_once "../Models/User.php";
require_once "../Models/Cart.php";
require_once "../Models/Product.php";
require_once "../Controllers/AuthController.php";
require_once "../Controllers/CartController.php";
require_once "../Controllers/ProductController.php";

$errors = [];
$input = trim($_POST["input"]);
$pswrd = $_POST["pswrd"];

$signin = new AuthController();


if(!$signin->login($input, $pswrd)){
  header("location: ../View/signin.php");
  exit();
};

$_SESSION["user"] = serialize($signin);

$cart = new CartController();

if(!$cart->get_user_cart(unserialize($_SESSION['user'])->id)){
  $cart->make_user_cart(unserialize($_SESSION['user'])->id);
};

$_SESSION['cart']['products'] = $cart->get_user_cart_products(unserialize($_SESSION['user'])->id);
$_SESSION['cart']['total_price'] = $cart->get_user_total_cost();

header("location:../View/index.php");
exit();