<?php
    require_once "conn.php";
    require_once "validate.php";

    $result = $conn->query("SELECT ca.*,
        c.CUSTOMER_Name,
        c.CUSTOMER_Address,
        c.CUSTOMER_PhoneNo
    
        FROM `customer_accumulation` ca
        LEFT JOIN `customer` c ON ca.Customer_Id = c.CUSTOMER_Id;");

    $output = array();
            
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc())
        {
            $output[] = $row;
        }
    }

    echo json_encode($output);

    $result -> close();
?>