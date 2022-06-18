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
$search = $search1 = $search2 = $username = $doctorid = $patientid = $doctorid = $date = $time = $message = $phone_no = "";
$date_err = $time_err = $message_err = $phone_no_err = "";
// Processing form data when form is submitted
//$doctorid = <?php echo $_GET['docid']?/$_GET['docid'
$search = $_POST["search"];
		$search1 = $_POST["search1"];
		$search2 = $_POST["search2"];

	
?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel = "stylesheet" href = "diagnosis.css">
	<title>Diagnosis</title>
</head>



<body id = "body">
	<div class = "bookingForm">
		<?php
		
		//$docid = $_GET['docid'];
	
	//echo "<script>alert('$docid');</script>";

	?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<form class="d-flex" role="search">
      <input class="date" type="search" placeholder = "Symptom 1"  aria-label="Search" name = "search">
	  <br>
	  <input class="date" type="search" placeholder = "Symptom 2"  aria-label="Search" name = "search1">
	  <br>
	  <input class="date" type="search" placeholder = "Symptom 3"  aria-label="Search" name = "search2">
	  <br>
      <button class="submitButton btn btn-outline-success" type="submit" name = "submit">Search</button>
    </form>
        </form>

		
</div>

<div id = "style1" class = "fiver">
       <?php

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "demo";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
	
		

        $sql = "SELECT diseaseName, diseaseInfo FROM diagnosis where diseaseInfo like '%$search%' or diseaseInfo like '%$search1%' or diseaseInfo like '%$search2%'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0 && isset($search)) {
            // output data of each row   ".row["userid"]" //<br>".$row["info"]."<br> ".$rwo["qualifications"]." ".$row["specializations"]." <a class = 'info123 signLog infosButtons'> <!--<p class = 'fullnameyo'>".$row["info"]."</p></a>
            while($row = $result->fetch_assoc()) {
                echo "<div class = 'everyElement'>
                            <p class = 'infos'> Disease Name: ".$row["diseaseName"]." <br> Symptoms: ".$row["diseaseInfo"]."<br></p>
                            </div>";
                echo "<br>";
                echo "<br>";
            }
        } else {
            echo "0 results";
        }

        $conn->close();
        ?>
        
</div>
</body>
</html>

