<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once "conn.php";

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate the input data
if (!isset($data['orderId'])) {
    echo json_encode(['response' => 'error', 'message' => 'Invalid input']);
    exit();
}

$orderId = $data['orderId'];

// Prepare the SQL statement to delete the order details
$sql1 = "DELETE FROM gas_order_detail WHERE Order_ID = ?";
$sql2 = "DELETE FROM gas_order WHERE ORDER_Id = ?";

if ($stmt1 = $conn->prepare($sql1)) {
    $stmt1->bind_param("i", $orderId);

    if ($stmt1->execute()) {
        $stmt1->close();

        if ($stmt2 = $conn->prepare($sql2)) {
            $stmt2->bind_param("i", $orderId);

            if ($stmt2->execute()) {
                echo json_encode(['response' => 'success', 'message' => 'Order deleted successfully']);
            } else {
                echo json_encode(['response' => 'error', 'message' => 'Failed to delete order']);
            }

            $stmt2->close();
        } else {
            echo json_encode(['response' => 'error', 'message' => 'Failed to prepare statement for order']);
        }
    } else {
        echo json_encode(['response' => 'error', 'message' => 'Failed to delete order details']);
    }
} else {
    echo json_encode(['response' => 'error', 'message' => 'Failed to prepare statement for order details']);
}

$conn->close();
?>
