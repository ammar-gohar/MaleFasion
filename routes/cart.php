<?php

require_once "../config.php";

if(!isset($_SESSION['user']) || !$_POST){
  header("location: ../view/shop.php");
  exit;
}

$quantity = $_POST['quantity'] ?? null;
$product = isset($_POST['size-color']) ? explode("-", $_POST['size-color']) : null;

if(!$product){
  header("location: ../view/shop.php");
  exit;
}

if(!($quantity || isset($_POST['delete'])) || preg_match("/[^0-9]/", $quantity) ){
  header("location: ../view/product.php?id=$productId");
  exit;
}

$productId = $product[0];
$id = $product[1];
$color = $product[2];
$size = $product[3];

require_once "../Models/User.php";
require_once "../Controllers/AuthController.php";
require_once "../Models/Cart.php";
require_once "../Models/Product.php";
require_once "../Controllers/ProductController.php";
require_once "../Controllers/CartController.php";

$cart = new CartController();

if(!$cart->get_user_cart(unserialize($_SESSION['user'])->id)){
  $cart->make_user_cart(unserialize($_SESSION['user'])->id);
};

if(isset($_POST['delete'])){
  $productCartId = $_POST['product_cart_id'];
  $cart->remove_product($productCartId, $id);
} else {
  $product = new ProductController();
  $product = $product->get_item($id);
  if($product['stock'] - $quantity < 0){
    $_SESSION['flash']['fail'] = "Only ". $product['stock'] ." is left from this item!";
    header("location: ../view/product.php?id=$productId");
    exit;
  }
  $price = $product['sale_price'] ?? $product['price'];
  $cart->add_to_cart($id, $quantity, $quantity * $price);
}

$_SESSION['cart']['products'] = $cart->get_user_cart_products(unserialize($_SESSION['user'])->id);
$_SESSION['cart']['total_price'] = $cart->get_user_total_cost();

header("location: ../view/cart.php");
exit;



