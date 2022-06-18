<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: welcome.php");
    exit;
}
?>
 
 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel = "stylesheet" href = "doctorDashboard.css">
	<title>KOHA - Doctor Dashboard</title>
</head>
<style><?php include 'doctorDashboard.css'; ?></style>
<body id = "body">
	<header>
		<div class = "navBar">
			<a href = "welcome.php"><img src = "kohaLogo.png" alt = "Logo" class = "logo"></a>
			<nav>
				<ul>
					<li><a class = "buttons" id = "home" href = "welcome.php" target = "_blank">Home</a></li>
					<li><a class = "buttons" id = "Patient" href = "registerPatient.php" target = "_blank">Patient</a></li>
					<li><a class = "buttons" id = "Patient" href = "registerDoctor.php" target = "_blank">Doctor</a></li>
                    <li><a href="logout.php" class="signLog btn btn-secondary ml-2">Log Out</a></li>
				</ul>
			</nav>
		</div>
	</header>
    <div class="sidenav">
        <img class ="profileImg" src = "pp6.jpg" alt = "Profile Picture">
        <p class = "nameProfile"><?php echo htmlspecialchars($_SESSION["username"]); ?><br><br><br><br><br><br><br></p>
        <br><br><br><br><br><br><br><br><br><br><br><br><br>
  <a class = "buttons sidenavShit" href="reset-password.php">Reset Password<br></a>

  
</div>
<!--<div class="wrap">
   <div class="search">
      <input type="text" class="searchTerm" placeholder="What are you looking for?">
      <button type="submit" class="searchButton">
        <i class="fa fa-search"></i>
     </button>
   </div>
</div>-->
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

        $docuserid = $_SESSION["username"];
        $docquery = "select userid from doctors1 where username = '".$docuserid."'";
		$ara = $conn->query($docquery);
		$doctorsid = $ara->fetch_array()[0] ?? '';
        $sql = "SELECT P.userid, P.username, P.symptoms, P.diagnosis, P.info, P.pp, A.date, A.time
        FROM patients1 as P, appointment1 as A
        WHERE P.userid = A.patientid AND  '".$doctorsid."' = A.doctorid";
        $result = $conn->query($sql);


        

        if ($result->num_rows > 0) {
            // output data of each row    //<br>".$row["info"]."<br> ".$rwo["qualifications"]." ".$row["specializations"]." <a class = 'info123 signLog infosButtons'> <!--<p class = 'fullnameyo'>".$row["info"]."</p></a>
            while($row = $result->fetch_assoc()) {
                echo "<div class = 'everyElement'>
                <img class ='profileImgDoc' src = 'pp3.jpg' alt = 'Profile Picture'>
                            <p class = 'infos'> Name: ".$row["username"]." <br> Symptoms: ".$row["symptoms"]."<br>Diagnosis: ".$row["diagnosis"]."<br>Info:  ".$row["info"]." <br>Appointement Date: ".$row["date"]." <br> Appointment time: ".$row["time"]."<br><br> <a href = 'doctorChat.php' class = 'signlog signLog infosButtons btn btn-secondary ml-2'>Chat</a><a href = 'videocallapi/express-demo-web-master/src/Examples/QuickStart/CommonUsage/index.html' class = 'signlog signLog infosButtons btn btn-secondary ml-2'>Video Call</a></p>
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
</html>


<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>


    <a href="doctorChat.php">Chat with patients</a>

</body>
</html>-->