<?php
    if (isset($_POST['gasId'])) {
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

        $stmt = $conn->prepare("UPDATE gas SET 
            TANK_Id = ?, 
            GAS_Company_Id = ?, 
            GAS_Weight_Empty = ?, 
            GAS_Type = ?, 
            GAS_Volume = ?, 
            GAS_Examine_Day = ?, 
            GAS_Notes = ? 
        WHERE GAS_Id = ?;");

        $stmt->bind_param("ssiisssi", $tankId, $company, $emptyWeight, $type, $volume, $examDay, $notes, $gasId);

        if($stmt->execute()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        echo json_encode($response);

        $stmt->close();
    }
?>