<?php
    if(isset($_POST['order_id']) && isset($_POST['worker_id']) && isset($_POST['customer_id'])) {
        require_once "conn.php";
        require_once "validate.php";

        $customer_id = validate($_POST['customer_id']);
        $worker_id = validate($_POST['worker_id']);
        $order_id = validate($_POST['order_id']);

        if ($worker_id != 0) {
            $stmt = $conn->prepare("INSERT INTO `assign`(`CUSTOMER_Id`, `WORKER_Id`, `ORDER_Id`) VALUES (?, ?, ?);");
            $stmt->bind_param("iii", $customer_id, $worker_id, $order_id);

            if($stmt->execute()) {
                $response['status'] = 'assign_success';
            } else {
                $response['status'] = 'failed';
            }

            echo json_encode($response);

        } else {
            $stmt = $conn->prepare("DELETE FROM `assign` WHERE `ORDER_Id` = ?;");
            $stmt->bind_param("i", $order_id);

            if($stmt->execute()) {
                $response['status'] = 'unassign_success';
            } else {
                $response['status'] = 'failed';
            }

            echo json_encode($response);
        }



        $stmt->close();
        $conn->close();
    }
?>