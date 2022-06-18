<?php
// Initialize the session
session_start();
 
//Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: doctorDashboard.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
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
}
?>
 

 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel = "stylesheet" href = "doctorLogin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<title>KOHA - Patient Login</title>
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
        <p>PLEASE FILL UP YOUR CREDENTIALS TO LOG IN.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>USERNAME: </label>
                <input type="text" placeholder = "Enter Username." name="username" class="date form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
             
            <div class="form-group">
                <labe>PASSWORD: </label>
                <input type="password" placeholder = "Enter Username" name="password" class="date form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            
            <div class="form-group">
                <input type="submit" class="submitButton btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a class = "signLog btn btn-secondary ml-2" href="registerDoctor.php">Sign up here</a></p>
        </form>
        
  
	</div>

	<div class = "docPatient">
		<p id = "main">DOCTOR LOG IN.</p></div>
	


</body>
</html>




 
<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Doctor - Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        /*if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }*/        
        ?>

        <form action="<?php //echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php //echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php //echo $username; ?>">
                <span class="invalid-feedback"><?php //echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php //echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php //echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="registerDoctor.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>-->