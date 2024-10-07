<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json");

    // 啟用錯誤報告
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // 包含必要的文件
    require_once "conn.php";
    require_once "validate.php";

    // 確認POST請求是否已收到
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Include the necessary files
        require_once "conn.php";
        require_once "validate.php";
    
        $input = json_decode(file_get_contents("php://input"), true);
    
        if (isset($input['customerId']) && isset($input['companyId']) && isset($input['deliveryCondition']) && isset($input['deliveryAddress']) && isset($input['deliveryPhone']) && isset($input['gasQuantity']) && isset($input['orderTime']) && isset($input['expectTime']) && isset($input['deliveryMethod']) && isset($input['sensorId'])) {
            $customerId = validate($input['customerId']);
            $companyId = validate($input['companyId']);
            $deliveryCondition = validate($input['deliveryCondition']);
            $deliveryAddress = validate($input['deliveryAddress']);
            $deliveryPhone = validate($input['deliveryPhone']);
            $gasQuantity = validate($input['gasQuantity']);
            $orderTime = validate($input['orderTime']);
            $expectTime = validate($input['expectTime']);
            $deliveryMethod = validate($input['deliveryMethod']);
            $sensorId = validate($input['sensorId']);
    
            $sql = "INSERT INTO gas_order (CUSTOMER_Id, COMPANY_Id, DELIVERY_Condition, DELIVERY_Address, DELIVERY_Phone, Gas_Quantity, Order_Time, Expect_time, Delivery_Method, Sensor_Id) VALUES ('$customerId', '$companyId', '$deliveryCondition', '$deliveryAddress', '$deliveryPhone', '$gasQuantity', '$orderTime', '$expectTime', '$deliveryMethod', '$sensorId')";
    
            if ($conn->query($sql)) {
                $orderId = (int)$conn->insert_id;
                echo json_encode(["response" => "success", "orderId" => $orderId]);
            } else {
                echo json_encode(["response" => "error", "message" => "Failed to insert order"]);
            }
        } else {
            echo json_encode(["response" => "error", "message" => "Invalid input"]);
        }
    } else {
        echo json_encode(["response" => "error", "message" => "Invalid request method"]);
    }

    // Get the input data
    $input = json_decode(file_get_contents("php://input"), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["response" => "error", "message" => "Invalid JSON input: " . json_last_error_msg()]);
        exit;
    }

    // Required fields
    $requiredFields = ['Order_ID', 'Quantity', 'Type', 'Weight', 'Exchange'];

    // Check for missing fields
    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            echo json_encode(["response" => "error", "message" => "Missing required field: $field"]);
            exit;
        }
    }

    // Validate and bind the input data
    $Order_ID = validate($input['Order_ID']);
    if (!$Order_ID) {
        echo json_encode(["response" => "error", "message" => "Invalid Order_ID"]);
        exit;
    }

    $Quantity = validate($input['Quantity']);
    if (!$Quantity) {
        echo json_encode(["response" => "error", "message" => "Invalid Quantity"]);
        exit;
    }

    $Type = validate($input['Type']);
    if (!$Type) {
        echo json_encode(["response" => "error", "message" => "Invalid Type"]);
        exit;
    }

    $Weight = validate($input['Weight']);
    if (!$Weight) {
        echo json_encode(["response" => "error", "message" => "Invalid Weight"]);
        exit;
    }

    $Exchange = validate($input['Exchange']);
    if (!$Exchange) {
        echo json_encode(["response" => "error", "message" => "Invalid Exchange"]);
        exit;
    }

    // SQL Insert
    $sql = "INSERT INTO gas_order_detail (Order_ID, Order_Quantity, Order_Type, Order_Weight, exchange) VALUES ('$Order_ID', '$Quantity', '$Type', '$Weight', '$Exchange')";

    if ($conn->query($sql)) {
        echo json_encode(["response" => "success", "message" => "Order details inserted successfully"]);
    } else {
        echo json_encode(["response" => "error", "message" => "Failed to insert order details: " . $conn->error]);
    }

?>