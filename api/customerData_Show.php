<?php
    require_once "conn.php";
    require_once "validate.php";

    $result = $conn->query("SELECT DISTINCT c.CUSTOMER_ID, 
       c.CUSTOMER_Name, 
       c.CUSTOMER_Sex, 
       c.CUSTOMER_PhoneNo, 
       c.CUSTOMER_Postal_Code, 
       c.CUSTOMER_Address, 
       c.CUSTOMER_HouseTelpNo, 
       c.CUSTOMER_Email, 
       c.CUSTOMER_FamilyMemberId, 
       c.CUSTOMER_Notes, 
       c.COMPANY_Id, 
       i.SENSOR_Id,
       i.Gas_Original_Weight, 
       i.Gas_Empty_Weight,
       
       CASE 
         WHEN gor.CUSTOMER_Id IS NOT NULL 
         THEN 'yes' 
       END AS has_record
       
        FROM `customer` c
        LEFT JOIN `iot` i ON c.CUSTOMER_ID = i.Customer_Id
        LEFT JOIN `gas_order` gor ON c.CUSTOMER_ID = gor.CUSTOMER_Id;");
                            

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