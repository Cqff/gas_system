<?php
    require_once "conn.php";
    require_once "validate.php";
    
    $id = validate($_POST['id']);
	//$id1 = validate($_POST['id1']);
	
	$result1 = $conn->query("SELECT * FROM iot WHERE iot.Customer_Id = '$id'");
	$result2 = $conn->query("SELECT * FROM sensor_history INNER JOIN iot ON iot.SENSOR_Id = sensor_history.SENSOR_Id WHERE iot.Customer_Id = '$id' ");
    

	if ($result1 && $result2) {
		$output = array(); // Initialize the output array

		while ($row1 = $result1->fetch_assoc()) {
			$sensorId = $row1['SENSOR_Id'];

			// Find the corresponding SENSOR_Weight for this SENSOR_Id
			$sensorWeight = null;
			$result2->data_seek(0); // Reset the pointer to the beginning of result2
			while ($row2 = $result2->fetch_assoc()) {
				if ($row2['SENSOR_Id'] === $sensorId) {
                $sensorWeight = $row2['SENSOR_Weight'];
				$Gas_Empty_Weight = $row1['Gas_Empty_Weight'];
				$Gas_remain = $sensorWeight - $Gas_Empty_Weight;
                break;
            }
        }

        // If a matching SENSOR_Weight was found, add the data to the output array
        if ($sensorWeight !== null) {
            $output[] = array('sensorId' => $sensorId, 'SENSOR_Weight' => $Gas_remain);
        }
    }


    // Convert the data array to JSON
    $jsonOutput = json_encode($output, JSON_UNESCAPED_UNICODE);

		// Print the JSON output
		print($jsonOutput);

		// Close the result sets
		$result1->close();
		$result2->close();
	} else {
		// Handle query errors
		$output[] = array('Error' => 'Query Error');
		$jsonOutput = json_encode($output, JSON_UNESCAPED_UNICODE);
		print($jsonOutput);
	}
    // Close the database connection
    $conn->close();
?>