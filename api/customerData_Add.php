<?php
    if(isset($_POST['phone']) && isset($_POST['email'])) {
        require_once "conn.php";
        require_once "validate.php";

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

        $time = validate($_POST['time']);

        $stmt = $conn->prepare("INSERT INTO customer (CUSTOMER_Name, CUSTOMER_Sex, CUSTOMER_PhoneNo, CUSTOMER_Postal_Code, CUSTOMER_Address, CUSTOMER_HouseTelpNo, CUSTOMER_Email, CUSTOMER_FamilyMemberId, CUSTOMER_Notes, COMPANY_Id, CUSTOMER_Registration_Time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiisisisis", $name, $sex, $phone, $code, $address, $houseTel, $email, $family, $notes, $company, $time);

        if($stmt->execute()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        $stmt1 = $conn->prepare("SELECT CUSTOMER_Id FROM customer WHERE CUSTOMER_PhoneNo = ?");
        $stmt1->bind_param("i", $phone);

        if ($stmt1->execute()) {
            // Store the result
            $stmt1->store_result();
        
            // Check if a row was returned
            if ($stmt1->num_rows > 0) {
                // Bind the result variable
                $stmt1->bind_result($customerId);
                
                // Fetch the result
                $stmt1->fetch();
                
                // Return the CUSTOMER_Id
                $response['id'] = $customerId; // Set the response to the retrieved ID
            }

        echo json_encode($response);

        $stmt->close();
        $stmt1->close();
        $conn->close();

        }
    }
?>