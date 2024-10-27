<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include 'conn.php';



$sql = "SELECT o.ORDER_Id, o.CUSTOMER_Id, c.CUSTOMER_PhoneNo, o.DELIVERY_Address, o.EXPECT_Time, c.CUSTOMER_Name, od.Order_type, od.Order_weight, o.Gas_Quantity, ca.Gas_Volume, a.WORKER_Id, w.WORKER_Name
                    FROM `gas_order` o
                    LEFT JOIN `customer` c ON o.CUSTOMER_Id = c.CUSTOMER_Id
                    LEFT JOIN `gas_order_detail` od ON o.ORDER_Id = od.Order_ID
                    LEFT JOIN `customer_accumulation` ca ON o.CUSTOMER_Id = ca.Customer_Id
                    LEFT JOIN `assign` a ON o.ORDER_Id = a.ORDER_Id
                    LEFT JOIN `worker` w ON a.WORKER_Id = w.WORKER_Id
                    WHERE o.DELIVERY_Condition = 0
                    GROUP BY o.ORDER_Id;";

$stmt = $conn->prepare($sql);

$stmt->execute();
$result = $stmt->get_result();

$orders = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

echo json_encode($orders);

$conn->close();
?>