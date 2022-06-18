<!DOCTYPE html>
<html>
 
<head>
    <title>Insert Page page</title>
</head>
 
<body>
    <center>

    <?php

 
        // Taking all 5 values from the form data(input)
        
         $date = $_REQUEST["date"];

		$time = $_REQUEST["time"];

		$message = $_REQUEST["Text1"];

		$phone_no = $_REQUEST["phone_no"];

		$username = $_SESSION["username"];

        $patientid = "Select userid from patients1 where username = '".$username."'";
        // Performing insert query execution
        // here our table name is college
        $sql = "INSERT INTO college  VALUES ('$first_name',
            '$last_name','$gender','$address','$email')";
         $sql = "INSERT INTO appointment1 (patientid, date, message, phoneno, time) VALUES ('$patientid', '$date', '$message', '$phone_no', '$time')";
         
        // Close connection
        mysqli_close($conn);
        ?>
    </center>
</body>
 
</html>