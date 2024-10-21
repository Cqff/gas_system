<?php
    if(isset($_POST['id'])) {
        require_once "conn.php";
        require_once "validate.php";

        $id = validate($_POST['id']);
        $name = validate($_POST['name']);
        $phone = validate($_POST['phone']);
        $city = validate($_POST['city']);
        $district = validate($_POST['district']);
        $address = validate($_POST['address']);
        $notes = validate($_POST['notes']);

        $stmt = $conn->prepare("UPDATE `company`
            SET
                `COMPANY_Name` = ?,
                `COMPANY_Phone_No` = ?,
                `COMPANY_City` = ?,
                `COMPANY_District` = ?,
                `COMPANY_Address` = ?,
                `company_notes` = ?
            WHERE `COMPANY_Id` = ?;");
        
        $stmt->bind_param("ssssssi", $name, $phone, $city, $district, $address, $notes, $id);

        if($stmt->execute()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        echo json_encode($response);

        $stmt->close();
        $conn->close();
    }
?>