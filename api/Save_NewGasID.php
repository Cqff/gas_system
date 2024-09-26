<?php
// Include the necessary files
require_once "conn.php";
require_once "validate.php";

// Call validate, pass form data as parameter and store the returned value
$order_Id = validate($_POST['id']);
$time = validate($_POST['time']);

$sql = "update gas_order set `DELIVERY_Time`='$time',`DELIVERY_Condition`='1' where `ORDER_Id`='$order_Id'";

$result = "";

if (!$conn->query($sql)) {
    echo "failure";
} else {
    echo "success";
}
?>
