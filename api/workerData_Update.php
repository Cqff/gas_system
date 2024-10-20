<?php
    if(isset($_POST['id']) && isset($_POST['phone']) && isset($_POST['email'])) {
        require_once "conn.php";
        require_once "validate.php";

        $id = validate($_POST['id']);
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

        $stmt = $conn->prepare("UPDATE worker 
            SET WORKER_Name = ?, 
                WORKER_Sex = ?, 
                WORKER_PhoneNum = ?, 
                WORKER_HouseTelpNo = ?, 
                WORKER_Email = ?, 
                WORKER_Address = ?, 
                Permission = ?, 
                WORKER_Company_Id = ?, 
                WORKER_Notes = ? 
            WHERE WORKER_Id = ?");

        $stmt->bind_param("siiissiisi", $name, $sex, $phone, $houseTel, $email, $address, $permission, $company, $notes, $id);

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