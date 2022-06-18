<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $qualifications = $specializations = $info = "";
$username_err = $password_err = $confirm_password_err = $qualifications_err = $specializations_err = $info_err = "";
 
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
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    if(empty(trim($_POST["qualifications"]))){
        $qualifications_err = "Please enter your qualifications.";     
    }else{
        $qualifications = trim($_POST["qualifications"]);
    }
        
    if(empty(trim($_POST["specializations"]))){
        $specializations_err = "Please enter your specializations.";     
    }else{
        $specializations = trim($_POST["specializations"]);
    }
    
    if(empty(trim($_POST["info"]))){
        $info_err = "Please enter your info.";     
    }else{
        $info = trim($_POST["info"]);
    }
    

    if (count($_FILES) > 0) {
        if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
            require_once "config.php";
            $imgData = addslashes(file_get_contents($_FILES['userImage']['tmp_name']));
            $imageProperties = getimageSize($_FILES['userImage']['tmp_name']);
            
            $sql = "INSERT INTO doctor1(ppname ,pp)
        VALUES('{$imageProperties['mime']}', '{$imgData}')";
            $current_id = mysqli_query($conn, $sql) or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_error($conn));
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($qualifications_err) && empty($specializations_err) && empty($info_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO doctors1 (username, password, qualifications, specializations, info) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_password, $param_qualifications, $param_specializations, $param_info);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_qualifications = $qualifications;
            $param_specializations = $specializations;
            $param_info = $info;

            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: loginDoctor.php");
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<title>KOHA - Doctor Sign up</title>
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
        <p>PLEASE FILL THIS FORM TO CREATE AN ACCOUNT</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>USERNAME: </label>
                <input type="text" name="username" placeholder="Enter Username." class="date form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="date invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
       
            <div class="form-group">
                <label>PASSWORD: </label>
                <input type="password" name="password" placeholder="Enter Password."  class="date form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
          
            <div class="form-group">
                <label>CONFIRM PASSWORD: </label>
                <input type="password" name="confirm_password" placeholder="Confirm Password." class="date form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
         
            <div class="form-group">
                <label>QUALIFICATIONS: </label>
                <input type="text" name="qualifications" placeholder="Enter Qualifications." class="date form-control <?php echo (!empty($qualifications_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $qualifications; ?>">
                <span class="invalid-feedback"><?php echo $qualifications_err; ?></span>
            </div>
         
            <div class="form-group">
                <label>SPECIALIZATIONS: </label>
                <input type="text" name="specializations" placeholder="Enter Specializations." class="date form-control <?php echo (!empty($specializations_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $specializations; ?>">
                <span class="invalid-feedback"><?php echo $specializations_err; ?></span>
            </div>
           
            <div class="form-group">
                <label>INFO: </label>
                <input type="text" name="info" placeholder="Enter Info." class="date form-control <?php echo (!empty($info_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $info; ?>">
                <span class="invalid-feedback"><?php echo $info_err; ?></span>
            </div>
            

            <!--<div class  ="form-group">
            <form name="frmImage" enctype="multipart/form-data" action=""
        method="post" class="frmImageUpload">
        <label>Upload profile picture:</label><br /> <input name="userImage"
            type="file" class="inputFile" /> <input type="submit"
            value="Submit" class="btnSubmit" />
        </form>
        </div>  



            <div class = "form-group">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <label>Select Image File:</label>
                <input type="file" name="image">
                <input type="submit" name="submit" value="Upload">
            </form>
            </div>-->

            <!--<div class="form-group">
                <label>PROFILE PICTURE NAME.TYPE: </label>
                <input type="file" name="imagefile" class="form-control <?//php echo (!empty($pp_err)) ? 'is-invalid' : ''; ?>" value="<?//php echo $pp; ?>">
                <span class="invalid-feedback"><?//php echo $pp_err; ?></span>
            </div>-->
            <div class="form-group">
                <input type="submit" class="submitButton btn btn-primary" value="Submit">
                <input type="reset" class="submitButton btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a class = "signLog btn btn-secondary ml-2" href="loginDoctor.php">Login here</a>.</p>
        </form>
	</div>

	<div class = "docPatient">
		<p id = "main">DOCTOR SIGN UP.</p></div>
	


</body>
</html>