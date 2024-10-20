<?php
    if(isset($_POST['phone']) && isset($_POST['email'])) {
        require_once "conn.php";
        require_once "validate.php";

        $name = validate($_POST['name']);
        
        $sex = validate($_POST['sex']);
        $sex = ($sex == 1) ? 1 : 0;

        $phone = validate($_POST['phone']);
        $houseTel = validate($_POST['houseTel']);
        $email = validate($_POST['email']);
        $address = validate($_POST['address']);

        $permission = validate($_POST['permission']);
        $permission = empty($permission)? 0 : $permission;
        
        $company = validate($_POST['company']);
        $company = empty($company)? 1 : $company;

        $notes = validate($_POST['notes']);

        $stmt = $conn->prepare("INSERT INTO worker (WORKER_Name, WORKER_Sex, WORKER_PhoneNum, WORKER_HouseTelpNo, WORKER_Email, WORKER_Address, Permission, WORKER_Company_Id, WORKER_Notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiissiis", $name, $sex, $phone, $houseTel, $email, $address, $permission, $company, $notes);

        if($stmt->execute()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        $stmt1 = $conn->prepare("SELECT WORKER_Id FROM worker WHERE WORKER_Email = ?");
        $stmt1->bind_param("s", $email);

        if ($stmt1->execute()) {
            // Store the result
            $stmt1->store_result();
        
            // Check if a row was returned
            if ($stmt1->num_rows > 0) {
                // Bind the result variable
                $stmt1->bind_result($workerId);
                
                // Fetch the result
                $stmt1->fetch();
                
                // Return the WORKER_Id
                $response['id'] = $workerId; // Set the response to the retrieved ID
            }
        
        }
        echo json_encode($response);

        $stmt->close();
        $stmt1->close();
        $conn->close();

            
    }
?>