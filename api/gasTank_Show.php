<?php
    require_once "conn.php";
    require_once "validate.php";

    // Get the filter from the POST request
    $filter = isset($_POST['filter']) ? $_POST['filter'] : '';

    // Adjust the query to use the filter for matching names or addresses
    $sql = "SELECT g.*, 
            c.CUSTOMER_Name, 
            c.CUSTOMER_Address
            FROM `gas` g
            LEFT JOIN `iot` i ON g.GAS_Id = i.GAS_Id 
            LEFT JOIN `customer` c ON i.CUSTOMER_ID = c.CUSTOMER_Id
            WHERE g.TANK_Id LIKE ? 
            OR g.GAS_Id LIKE ?
            OR g.GAS_Notes LIke ?
            ORDER BY g.TANK_Id DESC LIMIT 40;";

    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare($sql);

    // Add wildcard characters for partial matching
    $filter_param = "%" . $filter . "%";
    $stmt->bind_param("sss",$filter_param, $filter_param, $filter_param);  // "ss" means two strings
    
    $stmt->execute();
    $result = $stmt->get_result();

    $output = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $output[] = $row;
        }
    }

    echo json_encode($output);

    $stmt->close();
    $conn->close();
?>