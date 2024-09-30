<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once "conn.php";
require_once "validate.php";

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate the input data
if (!isset($data['orderId']) || !isset($data['orderDetails'])) {
    echo json_encode(['response' => 'error', 'message' => 'Invalid input']);
    exit();
}

$orderId = $data['orderId'];
$orderDetails = $data['orderDetails'];

// Prepare the SQL statement to update the order
$sql = "UPDATE orders SET order_details = ? WHERE order_id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("si", $orderDetails, $orderId);

    if ($stmt->execute()) {
        echo json_encode(['response' => 'success', 'message' => 'Order updated successfully']);
    } else {
        echo json_encode(['response' => 'error', 'message' => 'Failed to update order']);
    }

    $stmt->close();
} else {
    echo json_encode(['response' => 'error', 'message' => 'Failed to prepare statement']);
}

$conn->close();
?>
