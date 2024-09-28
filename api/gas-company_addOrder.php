<?php
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);

$orderDate = $data['orderDate'];
$customerName = $data['customerName'];
$orderContent = $data['orderContent'];
$quantity = $data['quantity'];
$unit = $data['unit'];
$unitPrice = $data['unitPrice'];
$totalPrice = $data['totalPrice'];
$status = $data['status'];
$remarks = $data['remarks'];

$sql = "INSERT INTO gas_order (order_date, customer_name, order_content, quantity, unit, unit_price, total_price, status, remarks)
        VALUES ('$orderDate', '$customerName', '$orderContent', '$quantity', '$unit', '$unitPrice', '$totalPrice', '$status', '$remarks')";

$response = array();
if ($conn->query($sql) === TRUE) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

echo json_encode($response);

$conn->close();
?>