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
$deliveryManId = $data['delivery-man'];
$expectTime = $data['expect-time'];

// Prepare the SQL statement to update the order
$sqlOrder = "UPDATE gas_order SET 
    CUSTOMER_Id = ?, 
    COMPANY_Id = ?, 
    DELIVERY_Time = ?, 
    DELIVERY_Condition = ?, 
    Exchange = ?, 
    DELIVERY_Address = ?, 
    DELIVERY_Phone = ?, 
    Gas_Quantity = ?, 
    Order_Time = ?, 
    Expect_time = ?, 
    Delivery_Method = ?, 
    sensor_id = ? 
    WHERE ORDER_Id = ?";

if ($stmtOrder = $conn->prepare($sqlOrder)) {
    $stmtOrder->bind_param("iisssssissiii", 
        $data['CUSTOMER_Id'], 
        $data['COMPANY_Id'], 
        $data['DELIVERY_Time'], 
        $data['DELIVERY_Condition'], 
        $data['Exchange'], 
        $data['DELIVERY_Address'], 
        $data['DELIVERY_Phone'], 
        $data['Gas_Quantity'], 
        $data['Order_Time'], 
        $expectTime, 
        $data['Delivery_Method'], 
        $data['sensor_id'], 
        $orderId
    );

    if ($stmtOrder->execute()) {
        // Prepare the SQL statement to update the order details
        $sqlOrderDetail = "UPDATE gas_order_detail SET 
            Order_Quantity = ?, 
            Order_type = ?, 
            Order_weight = ?, 
            exchange = ? 
            WHERE Order_ID = ?";

        if ($stmtOrderDetail = $conn->prepare($sqlOrderDetail)) {
            $stmtOrderDetail->bind_param("issii", 
                $data['Order_Quantity'], 
                $data['Order_type'], 
                $data['Order_weight'], 
                $data['exchange'], 
                $orderId
            );

            if ($stmtOrderDetail->execute()) {
                // Update or insert into the assign table
                $checkAssignmentQuery = "SELECT COUNT(*) FROM assign WHERE ORDER_Id = ?";
                if ($stmtCheckAssignment = $conn->prepare($checkAssignmentQuery)) {
                    $stmtCheckAssignment->bind_param("i", $orderId);
                    $stmtCheckAssignment->execute();
                    $stmtCheckAssignment->bind_result($assignmentCount);
                    $stmtCheckAssignment->fetch();
                    $stmtCheckAssignment->close();

                    if ($assignmentCount > 0) {
                        // Update the assignment
                        $updateAssignmentQuery = "UPDATE assign SET WORKER_Id = ? WHERE ORDER_Id = ?";
                        if ($stmtUpdateAssignment = $conn->prepare($updateAssignmentQuery)) {
                            $stmtUpdateAssignment->bind_param("ii", $deliveryManId, $orderId);
                            $stmtUpdateAssignment->execute();
                            $stmtUpdateAssignment->close();
                        }
                    } else {
                        // Insert a new assignment
                        $insertAssignmentQuery = "INSERT INTO assign (CUSTOMER_Id, WORKER_Id, ORDER_Id) VALUES (?, ?, ?)";
                        if ($stmtInsertAssignment = $conn->prepare($insertAssignmentQuery)) {
                            $stmtInsertAssignment->bind_param("iii", $data['CUSTOMER_Id'], $deliveryManId, $orderId);
                            $stmtInsertAssignment->execute();
                            $stmtInsertAssignment->close();
                        }
                    }
                }

                echo json_encode(['response' => 'success', 'message' => 'Order and order details updated successfully']);
            } else {
                echo json_encode(['response' => 'error', 'message' => 'Failed to update order details']);
            }

            $stmtOrderDetail->close();
        } else {
            echo json_encode(['response' => 'error', 'message' => 'Failed to prepare order details statement']);
        }
    } else {
        echo json_encode(['response' => 'error', 'message' => 'Failed to update order']);
    }

    $stmtOrder->close();
} else {
    echo json_encode(['response' => 'error', 'message' => 'Failed to prepare order statement']);
}

$conn->close();
?>