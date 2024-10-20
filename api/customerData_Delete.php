<?php
    if (isset($_POST['id'])) {
        require_once "conn.php";
        require_once "validate.php";

        $id = validate($_POST['id']);

        $stmt = $conn->prepare("DELETE FROM customer WHERE CUSTOMER_Id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        echo json_encode($response);

        $stmt->close();
        $conn->close();
    }
?>