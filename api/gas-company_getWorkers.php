<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include 'conn.php';

$sql = "SELECT WORKER_Id, WORKER_Name FROM worker";
$result = $conn->query($sql);

$workers = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $workers[] = $row;
    }
}

echo json_encode($workers);

$conn->close();
?>
