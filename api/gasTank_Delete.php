<?php
    if(isset($_POST['id'])) {
        require_once "conn.php";
        require_once "validate.php";

        $id = validate($_POST['id']);
    
        $stmt = $conn->prepare("DELETE FROM gas WHERE GAS_Id = ?;");
        $stmt->bind_param("s" , $id);

        if($stmt->execute()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        echo json_encode($response);

        $stmt->close();
    }
?>