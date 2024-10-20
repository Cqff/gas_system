<?php
    if(isset($_POST['gasId'])) {
        require_once "conn.php";
        require_once "validate.php";

        $tankId = validate($_POST['tankId']);
        $gasId = validate($_POST['gasId']);
        
        $company = validate($_POST['company']);
        $company = empty($company)? 1 : $company;
        
        $emptyWeight = validate($_POST['emptyWeight']);
        $type = validate($_POST['type']);
        $volume = validate($_POST['volume']);
        $examDay = validate($_POST['examDay']);
        $notes = validate($_POST['notes']);

        $stmt = $conn->prepare("INSERT INTO gas (TANK_Id, GAS_Id, GAS_Company_Id, GAS_Weight_Empty, GAS_Type, GAS_Volume, GAS_Examine_Day, GAS_Notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("ssiisiss", $tankId, $gasId, $company, $emptyWeight, $type, $volume, $examDay, $notes);

        if($stmt->execute()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        echo json_encode($response);

        $stmt->close();
    }
?>