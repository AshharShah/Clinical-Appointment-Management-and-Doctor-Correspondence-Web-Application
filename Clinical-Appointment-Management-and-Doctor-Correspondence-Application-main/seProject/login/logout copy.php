<?php 
SESSION_START();
include ('Chat.php');
$chat = new Chat();
$chat->updateUserOnline($_SESSION['userid'], 0);
$chat2 = new Chat('doctors1');
$chat2->updateUserOnline($_SESSION['userid'], 0);
$chat3 = new Chat('patients1');
$chat3->updateUserOnline($_SESSION['userid'], 0);
$_SESSION['username'] = "";
$_SESSION['userid']  = "";
$_SESSION['login_details_id']= "";
header("Location:index.php");
?>






