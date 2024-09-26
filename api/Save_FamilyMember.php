<?php
if (isset($_POST['id'])) {
    // Include the necessary files
    require_once "conn.php";
    require_once "validate.php";

    // Call validate, pass form data as parameter and store the returned value
    $id = validate($_POST['id']);
    $Family_Phone = validate($_POST['Family_Phone']);
    $Family_id = null;
    $customerName = "";

    $sql0 = "SELECT CUSTOMER_Id FROM customer WHERE CUSTOMER_PhoneNo='$Family_Phone'";
    $result0 = $conn->query($sql0);
    if ($result0->num_rows > 0) {
        while ($row = $result0->fetch_assoc()) {
            $Family_id = $row['CUSTOMER_Id'];
        }
    }
    else{
        echo "No Customer in the database";
        exit;
    }

    $sql = "SELECT * FROM family WHERE Customer_Id='$id' ORDER BY dependency_Num DESC LIMIT 1";
    $result = $conn->query($sql);

    $sql2 = "SELECT * FROM family WHERE Dep_Cus_Id='$id'";
    $result2 = $conn->query($sql2);

    $sql3 = "SELECT CUSTOMER_Name FROM customer WHERE CUSTOMER_Id='$Family_id'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows > 0) {
        while ($row = $result3->fetch_assoc()) {
            $customerName = $row['CUSTOMER_Name'];
        }
    }

    //可能回有多筆
    if ($result->num_rows > 0) {
        // insert($id, "Dependency 數字新增", $Family_id, family_member_name)
        // $id, $Family_id, $customer_name
        $row = mysqli_fetch_assoc($result);

        $Dependency_Num = $row['Dependency_Num'];
        $Dependency_Num++;

        $sql_1 = "INSERT INTO family (`Customer_Id`, `Dependency_Num`, `Dep_Cus_Id`, `Customer_Name`) VALUES ('$id', '$Dependency_Num', '$Family_id', '$customerName')";
        if (!$conn->query($sql_1)) {
            echo "failure";
        } else {
            echo "success";
        }
    } elseif ($result2->num_rows > 0) {
        // 先回去抓到回先的@customer_id
        // insert($customer_id, "dependency 數字新增", $family_id, $family_member_name)
        $sql2_1 = "SELECT Customer_Id FROM family WHERE Dep_Cus_Id='$id'";
        $result2_1 = $conn->query($sql2_1);
        if ($result2_1->num_rows > 0) {
            while ($row2_1 = $result2_1->fetch_assoc()) {
                $Customer_Id = $row2_1['Customer_Id'];
            }
            
            // 釋放結果集
            $result2_1->free_result();
    
            $sql2_2 = "SELECT * FROM family WHERE Customer_Id='$Customer_Id' ORDER BY dependency_Num DESC LIMIT 1";
            $result2_2 = $conn->query($sql2_2);
            $row2_2 = mysqli_fetch_assoc($result2_2);
            $Dependency_Num = $row2_2['Dependency_Num'];
            $Dependency_Num++;
    
            $sql2_3 = "INSERT INTO family (`Customer_Id`, `Dependency_Num`, `Dep_Cus_Id`, `Customer_Name`) VALUES ('$Customer_Id', '$Dependency_Num', '$Family_id', '$customerName')";
            if (!$conn->query($sql2_3)) {
                echo "failure";
            } else {
                echo "success";
            }
    
            // 釋放結果集
            $result2_2->free_result();
        }
    }
    
    } else {
        // insert($id, "dependency 數字新增", $family_id, $family_member_name)
        $sql_1 = "INSERT INTO family (`Customer_Id`, `Dependency_Num`, `Dep_Cus_Id`, `Customer_Name`) VALUES ('$id', '1', '$Family_id', '$customerName')";
        if (!$conn->query($sql_1)) {
            echo "failure";
        } else {
            echo "success";
        }
    }
?>
