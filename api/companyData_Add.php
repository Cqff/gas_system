<?php
    require_once "conn.php";
    require_once "validate.php";

    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $city = validate($_POST['city']);
    $district = validate($_POST['district']);
    $address = validate($_POST['address']);
    $notes = validate($_POST['notes']);
    $time = validate($_POST['time']);

    $stmt = $conn->prepare("INSERT INTO `company`(
                `COMPANY_Name`,
                `COMPANY_Phone_No`,
                `COMPANY_City`,
                `COMPANY_District`,
                `COMPANY_Address`,
                `company_notes`,
                `COMPANY_Registration_Time`
            )
            VALUES(
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?);");
    $stmt->bind_param("sssssss", $name, $phone, $city, $district, $address, $notes, $time);

    if($stmt->execute()) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'failed';
    }

    $stmt1 = $conn->prepare("SELECT COMPANY_Id FROM company WHERE COMPANY_Phone_No = ?");
        $stmt1->bind_param("s", $phone);

        if ($stmt1->execute()) {
            // Store the result
            $stmt1->store_result();
        
            // Check if a row was returned
            if ($stmt1->num_rows > 0) {
                // Bind the result variable
                $stmt1->bind_result($companyId);
                
                // Fetch the result
                $stmt1->fetch();
                
                // Return the CUSTOMER_Id
                $response['id'] = $companyId; // Set the response to the retrieved ID
            }

        echo json_encode($response);

        $stmt->close();
        $stmt1->close();
        $conn->close();

        }
?>