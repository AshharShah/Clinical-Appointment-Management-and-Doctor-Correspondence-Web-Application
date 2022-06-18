<?php

/*if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT userid, username, password FROM doctors1 WHERE username = ?";
  
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            include ('Chat.php');
                            $chat = new Chat('doctors1');
                            
                            $user = $chat->loginUsers($_POST['username'], $hashed_password);
                                
                            if(!empty($user)) {
                                $_SESSION['username'] = $user[0]['username'];
                                $_SESSION['userid'] = $user[0]['userid'];
                                $chat->updateUserOnline($user[0]['userid'], 1);
                                $lastInsertId = $chat->insertUserLoginDetails($user[0]['userid']);
                                $_SESSION['login_details_id'] = $lastInsertId;
                            } else {
                                $loginError = "Invalid username or password!";
                            }
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: doctorDashboard.php");
                            exit;
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}*/
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: welcome.php");
    exit;
}

require_once "config.php";
 
// Define variables and initialize with empty values
$username = $doctorid = $patientid = $doctorid = $date = $time = $message = $phone_no = "";
$date_err = $time_err = $message_err = $phone_no_err = "";
// Processing form data when form is submitted
//$doctorid = <?php echo $_GET['docid']?/$_GET['docid'];

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    
    // Validate password
    
		$date = $_POST["date"];

		$time = $_POST["time"];

		$message = $_POST["Text1"];

		$phone_no = $_POST["phone_no"];
		
		$doctorid = $_POST["docc"];
		
		//$username = $_SESSION["username"];
		$username = $_SESSION["username"];
    
    // Check input errors before inserting in database
    if(empty($date_err) && empty($time_err) && empty($message_err) && empty($phone_err)){
        
        // Prepare an insert statement
		
        $sql = "INSERT INTO appointment1 (patientid, doctorid, date, message, phoneno, time) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iissss", $param_patientid, $param_doctorid, $param_date, $param_message, $param_phone_no, $param_time);
            
            // Set parameters

			$query = "select userid from patients1 where username = '".$username."'";
			$ara = $link->query($query);
			$param_patientid = $ara->fetch_array()[0] ?? '';

			$param_doctorid = $doctorid;
            $param_date = $date;
            $param_message = $message;
            $param_phone_no = $phone_no;
            $param_time = $time;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                //mysqli_stmt_close($stmt);
               // mysqli_close($link);
			   
                header("location: patientDashboard.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
//$_SESSION["username"];
// Taking all 5 values from the form data(input)
        
/*if($_SERVER["REQUEST_METHOD"] == "POST"){
	$date = $_REQUEST["date"];

$time = $_REQUEST["time"];

$message = $_REQUEST["Text1"];

$phone_no = $_REQUEST["phone_no"];



$patientid = "Select userid from patients1 where username = '".$username."'";
// Performing insert query execution
// here our table name is college

 $sql = "INSERT INTO appointment1 (patientid, date, message, phoneno, time) VALUES ('$patientid', '$date', '$message', '$phone_no', '$time')";
 mysqli_query($link, $sql);
 mysqli_close($link);
}*/
?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel = "stylesheet" href = "pb.css">
	<title>Booking</title>
</head>



<body id = "body">
	<div class = "bookingForm">
		<?php
		
		//$docid = $_GET['docid'];
	
	//echo "<script>alert('$docid');</script>";

	?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<p class = "texty"> Fill the Following Form to Book an Appointement. </p>  
			
  			<label class = "lab" for="date">Date: </label>
  			<input type="date" class ="date <?php echo (!empty($time_err)) ? 'is-invalid' : ''; ?>" name="date">
			<br>
			<br>
  			<label class = "lab" for="time">Select a time:</label>
  			<input type="time" class ="time <?php echo (!empty($time_err)) ? 'is-invalid' : ''; ?>" name="time">
			  

			  <input type="text" value="<?php echo $_GET['docid']?>" class ="lap <?php echo (!empty($time_err)) ? 'is-invalid' : ''; ?>" name="docc">
			  <br>
			<br>
            <div class="form-group">
                <label class = "lab">Note for the Doctor: </label>
				<br>
				<textarea name="Text1" placeholder="Enter Info." class = "note form-control <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>" cols="40" rows="5"><?php echo $message; ?></textarea>
                <!--<input type="textarea" name="message" value = "Enter Info." size = "2" autofocus= "autofocus" class="note form-control <?php //echo (!empty($message_err)) ? 'is-invalid' : ''; ?>">
                <span class="message invalid-feedback"><?php //echo $message_err; ?></span>-->
            </div>
			<br>
			<br>
			<div class="form-group">
                <label class = "lab">Enter Phone No: </label>
                <input type="textarea" name="phone_no" placeholder="Enter phone no."  autofocus= "autofocus" class="date form-control <?php echo (!empty($phone_no_err)) ? 'is-invalid' : ''; ?>">
            </div>
			<br>
			<br>
            <div class="form-group">
                <input type="submit" class="submitButton btn btn-primary" value="Submit">
			</div>
        </form>
</div>
</body>
</html>

