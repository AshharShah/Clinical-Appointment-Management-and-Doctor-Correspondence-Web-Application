<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $info = $profilePic = "";
$username_err = $info_err = $profilePic_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT userid FROM doctors1 WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate info
    if(empty(trim($_POST["info"]))){
        $info_err = "Please enter a info.";     
    } elseif(strlen(trim($_POST["info"])) < 6){
        $info_err = "info must have atleast 6 characters.";
    } else{
        $info = trim($_POST["info"]);
    }
    
    // Validate info
    if(empty(trim($_POST["profilePic"]))){
        $info_err = "Please enter a profile pic.";     
    } 
    else{
        $info = trim($_POST["info"]);
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($info_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO doctorprofile (username, info, profilePic) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_info, $param_profilePic);
            
            // Set parameters
            $param_username = $username;
            $param_info = info_hash($info, info_DEFAULT); // Creates a info hash
            $param_profilePic = $profilePic;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: doctorDashboard.php");
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
?>
 
 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel = "stylesheet" href = "doctorSignup.css">

	<title>M(O_O)BA</title>
</head>
<body id = "body">
	<header>
		<div class = "navBar">
			<a href = "welcome.php"><img src = "kohaLogo.png" alt = "Logo" class = "logo"></a>
			<nav>
				<ul>
					<li><a class = "buttons" id = "home" href = "welcome.php" target = "_blank">Home</a></li>
					<li><a class = "buttons" id = "Patient" href = "registerPatient.php" target = "_blank">Patient</a></li>
					<li><a class = "buttons" id = "Patient" href = "registerDoctor.php" target = "_blank">Doctor</a></li>
				</ul>
			</nav>
		</div>
		
	</header>
		
		<div class = "imageAndName">
        <p>PLEASE FILL THIS FORM TO CREATE YOUR PROFILE</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>USERNAME: </label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>INFO: </label>
                <input type="info" name="info" class="form-control <?php echo (!empty($info_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $info; ?>">
                <span class="invalid-feedback"><?php echo $info_err; ?></span>
            </div>
            <div class="form-group">
                <label>PROFILE PIC: </label>
                <input type="info" name="profilePic" class="form-control <?php echo (!empty($phoneNo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $profilePic; ?>">
                <span class="invalid-feedback"><?php echo $profilePic_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Go to Dashboard <a class = "signLog" href="patientDashboard.php">Click here</a>.</p>
        </form>
	</div>

	<div class = "docPatient">
		<p id = "main">DOCTOR SIGN UP.</p></div>
	


</body>
</html>