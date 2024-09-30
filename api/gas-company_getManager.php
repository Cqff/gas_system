<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (isset($_GET['managerId'])) {
    require_once "conn.php";
    require_once "validate.php";

    // Call validate, pass form data as parameter and store the returned value
    $managerId = validate($_GET['managerId']);

    $stmt = $conn->prepare("SELECT MANAGER_Name FROM manager_account WHERE Manager_ID = ?");
    $stmt->bind_param("i", $managerId);
    $stmt->execute();
    $stmt->bind_result($managerName);
    $stmt->fetch();

    if ($managerName) {
        $response = array("response" => "success", "managerName" => $managerName);
    } else {
        $response = array("response" => "error", "message" => "Manager not found");
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(array("response" => "error", "message" => "Invalid request"));
}
?>
