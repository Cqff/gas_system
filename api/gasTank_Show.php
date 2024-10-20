<?php
    require_once "conn.php";
    require_once "validate.php";

    $result = $conn->query("SELECT g.*,
        c.CUSTOMER_Name,
        c.CUSTOMER_Address

        FROM `gas` g
        LEFT JOIN `iot` i ON g.GAS_Id = i.GAS_Id 
        LEFT JOIN `customer` c ON i.CUSTOMER_ID = c.CUSTOMER_Id
        ORDER BY g.TANK_Id DESC;");

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