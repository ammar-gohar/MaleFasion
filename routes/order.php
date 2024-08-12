<?php

require_once "../config.php";

if(!isset($_SESSION['user']) || !isset($_SESSION['cart']) || count($_SESSION['cart']['products']) == 0){
  header("location: ../view/shop.php");
  exit;
}

require_once "../Models/User.php";
require_once "../Models/Cart.php";
require_once "../Models/Product.php";
require_once "../Models/Order.php";
require_once "../Controllers/OrderController.php";
require_once "../Controllers/AuthController.php";
require_once "../Controllers/ProductController.php";
require_once "../Controllers/CartController.php";

$products = $_SESSION['cart']['products'];
$totalPrice = $_SESSION['cart']['total_price'];
$productCartIds = [];
$orderProducts = [];
$pro = new ProductController();


foreach ($products as $product) {
  $productCartIds[] = $product['product_cart_id'];
  $orderProducts[] = [
    'variant_id' => $product['variant_id'],
    'quantity'   => $product['quantity'],
    'price'      => $product['price'],
  ];
  $pro->update_varitation($product['variant_id'], ['stock' => ($product['stock'] - $product['quantity']) ]);
}

$cart = new CartController();
$cart->assign_products_to_ordered($productCartIds);

$order = new OrderController();
$order->new_order(unserialize($_SESSION['user'])->id, $totalPrice);
$order->add_products($orderProducts);


$_SESSION['cart']['products'] = [];
$_SESSION['cart']['total_price'] = 0;

header("location: ../view/cart.php");
exit;