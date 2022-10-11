<?php
session_start();
 
// Clears all of the session variables
$_SESSION = array();
 
session_destroy();
 
// Goes back to the login page
header("location: login.php");
exit;
?>