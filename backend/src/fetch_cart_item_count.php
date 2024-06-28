<?php
session_start();
header('Content-Type: application/json');

$cartItemCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
echo json_encode(['cartItemCount' => $cartItemCount]);
?>
