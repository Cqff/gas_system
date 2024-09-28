<?php
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    require_once "conn.php";
    require_once "validate.php";

    // Call validate, pass form data as parameter and store the returned value
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    $stmt = $conn->prepare("SELECT Manager_ID FROM manager_account WHERE MANAGER_Email = ? AND MANAGER_Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->bind_result($managerId);
    $stmt->fetch();

    if ($managerId) {
        $response = array("response" => "success", "managerId" => $managerId);
    } else {
        $response = array("response" => "error");
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(array("response" => "error", "message" => "Invalid request"));
}
?>

