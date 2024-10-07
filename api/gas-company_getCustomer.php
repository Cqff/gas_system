<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
require_once "conn.php";

$sql = "SELECT CUSTOMER_Id, CUSTOMER_Name FROM customer WHERE COMPANY_Id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $companyId);
    $companyId = $_GET['companyId']; // Assuming companyId is passed as a query parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $workers = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($workers);
    $stmt->close();
} else {
    echo json_encode(['response' => 'error', 'message' => 'Failed to fetch delivery men']);
}

$conn->close();
?>
