<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION['username'] = "";
$_SESSION['userid']  = "";
$_SESSION['login_details_id']= "";
$_SESSION = array();

include ('Chat.php');
$chat = new Chat();
$chat->updateUserOnline($_SESSION['userid'], 0);
$chat2 = new Chat('doctors1');
$chat2->updateUserOnline($_SESSION['userid'], 0);
$chat3 = new Chat('patients1');
$chat3->updateUserOnline($_SESSION['userid'], 0);

// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: welcome.php");
exit;
?>