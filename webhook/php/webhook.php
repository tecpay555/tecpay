<?php
// Merchant secret key
$secret_key = "your_secret_key";

// Data received from gateway
$order_id = $_POST['order_id'];
$amount = $_POST['amount'];
$status = $_POST['status'];
$post_hash = $_POST['post_hash'];

// Compute the payment hash locally
$local_hash = md5($order_id.$amount.$status.$secret_key);

if ($post_hash == $local_hash) {
  // Mark the transaction as success & process the order
  // You can write code process the order herer
  $hash_status = "Hash Matched";
  $acknowledge='yes';
}
else {
  // Suspicious payment, dont process this payment.
  $hash_status = "Hash Mismatch";
  $acknowledge='no';
}

$data['hash_status']=$hash_status; 
$data['acknowledge']=$acknowledge;
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);  
?>
