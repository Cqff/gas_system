<?php
    require_once "conn.php";
    require_once "validate.php";

    $result = $conn->query("SELECT * FROM `company`;");
                            

    $output = array();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc())
        {
            $output[] = $row;
        }
    }

    echo json_encode($output);

    $result -> close();

?>