<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: login.php");
    exit;
}

// <meta http-equiv="refresh" content="900;url=logout.php" /> logs us out after 15 minutes, 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="900;url=logout.php" /> 
    <title>Welcome</title>
    <link rel="stylesheet" href="styles.css">
</head>
<section class="welcome">
<body>
    <h1 class="textBx" style="align-self: center" >Hi, <b><?php echo htmlspecialchars($_SESSION["FirstName"]); ?></b>. Welcome to JobHunt.</h1>
    <div class="btn">

        <a href="EmployerRegistration.php" class="btn btn-warning">Sign up as an employer.</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>

</div>
</body>
</section>
</html>