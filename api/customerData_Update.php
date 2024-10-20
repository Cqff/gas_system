<?php
    if(isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['id'])) {
        require_once "conn.php";
        require_once "validate.php";
        
        $id = validate($_POST['id']);
        $name = validate($_POST['name']);
        
        $sex = validate($_POST['sex']);
        $sex = ($sex == 1) ? 1 : 0;

        $phone = validate($_POST['phone']);
        $code = validate($_POST['code']);
        $address = validate($_POST['address']);
        $houseTel = validate($_POST['houseTel']);
        $email = validate($_POST['email']);
        $family = validate($_POST['family']);
        $notes = validate($_POST['notes']);
        
        $company = validate($_POST['company']);
        $company = empty($company)? 1 : $company;

        $stmt = $conn->prepare("UPDATE customer SET 
            CUSTOMER_Name = ?, 
            CUSTOMER_Sex = ?, 
            CUSTOMER_PhoneNo = ?, 
            CUSTOMER_Postal_Code = ?, 
            CUSTOMER_Address = ?, 
            CUSTOMER_HouseTelpNo = ?, 
            CUSTOMER_Email = ?, 
            CUSTOMER_FamilyMemberId = ?, 
            CUSTOMER_Notes = ?, 
            COMPANY_Id = ?
            WHERE CUSTOMER_Id = ?");

        $stmt->bind_param("siiisisisii", $name, $sex, $phone, $code, $address, $houseTel, $email, $family, $notes, $company, $id);

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