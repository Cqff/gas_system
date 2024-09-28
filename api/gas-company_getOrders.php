<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gas_company";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM gas_order";
$result = $conn->query($sql);

$orders = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

echo json_encode($orders);

$conn->close();
?>