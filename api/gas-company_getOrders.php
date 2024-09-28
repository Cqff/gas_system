<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include 'conn.php';

$companyId = '1'; // Assuming companyId is passed as a query parameter

$sql = "SELECT o.ORDER_Id, o.CUSTOMER_Id, c.CUSTOMER_PhoneNo, o.DELIVERY_Address, o.EXPECT_Time, od.exchange, c.CUSTOMER_Name, od.Order_type, od.Order_weight, o.Gas_Quantity, ca.Gas_Volume, a.WORKER_Id, w.WORKER_Name, o.sensor_id,
                        (
                            SELECT ROUND(((sh.SENSOR_Weight / 1000) - iot.Gas_Empty_Weight), 1) AS CurrentGasAmount
                            FROM `sensor_history` sh
                            JOIN `iot` iot ON sh.SENSOR_Id = iot.SENSOR_Id
                            WHERE iot.CUSTOMER_Id = o.CUSTOMER_Id
                            ORDER BY sh.SENSOR_Time DESC
                            LIMIT 1
                        ) AS CurrentGasAmount
                    FROM `gas_order` o
                    LEFT JOIN `customer` c ON o.CUSTOMER_Id = c.CUSTOMER_Id
                    LEFT JOIN `gas_order_detail` od ON o.ORDER_Id = od.Order_ID
                    LEFT JOIN `customer_accumulation` ca ON o.CUSTOMER_Id = ca.Customer_Id
                    LEFT JOIN `assign` a ON o.ORDER_Id = a.ORDER_Id
                    LEFT JOIN `worker` w ON a.WORKER_Id = w.WORKER_Id
                    WHERE o.COMPANY_Id = ?
                    AND o.DELIVERY_Condition = 0";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $companyId);
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