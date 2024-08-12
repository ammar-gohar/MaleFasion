<?php

$user = (object) [
  "id"       => 1,
  "username" => "ahmed234",
  "email"    => "email@x.com",
  "password" => "123456",
];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  print_r($input = json_decode(file_get_contents('php://input'), true));
}

echo json_encode($user);