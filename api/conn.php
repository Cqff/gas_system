<?php
// Create 4 variables to store these information
$server="localhost";
$username="root";
$password="";
$database = "gas_station";
// Create connection
$conn = new mysqli($server, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?> 