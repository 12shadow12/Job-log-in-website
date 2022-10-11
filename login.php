<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
{
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$Email = $Password = "";
$Email_err = $Password_err = $login_err = "";

// Processes the form data when the form data is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // If the email is empty, send an email error message
    if(empty(trim($_POST["Email"])))
    {
        $Email_err = "Please enter Email.";
    } 
    else
    {
        $Email = trim($_POST["Email"]);
    }
    
    // If the password is empty, send a password error message
    if(empty(trim($_POST["Password"])))
    {
        $Password_err = "Please enter your Password.";
    } 
    else
    {
        $Password = trim($_POST["Password"]);
    }
    
    // Check if there is no error regarding the email and password
    if(empty($Email_err) && empty($Password_err))
    {
        // Prepare a select statement
        $sql = "SELECT UserID, Email, Password, FirstName FROM useraccount WHERE Email = ?";
        
        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_Email);
            
            // Set parameters
            $param_Email = $Email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if Email exists, if yes then verify Password
                if(mysqli_stmt_num_rows($stmt) == 1)
                {                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $UserID, $Email, $hashed_Password, $FirstName);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($Password, $hashed_Password))
                        {
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["UserID"] = $UserID;
                            $_SESSION["Email"] = $Email;     
                            $_SESSION["FirstName"] = $FirstName;                       
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } 
                        else
                        {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid Email or password.";
                        }
                    }
                } 
                else
                {
                    // Email doesn't exist, display a generic error message
                    $login_err = "Invalid Email or password.";
                }
            } 
            else
            {
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
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

	<header>
		<a href="login.php" class="logo" >JobHunt</a>
		<div class="toggle"></div>
		<ul>
			<li><a href="#"></a></li>
		</ul>
	</header>

<section class="banner">
	<div id="rectangle">
    <div class="textBx">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger" style="color:red">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" placeholder="Email" name="Email" class="form-control <?php echo (!empty($Email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Email; ?>">
				<span class="invalid-feedback" style="color:red"><?php echo $Email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" placeholder="Password" name="Password" class="form-control <?php echo (!empty($Password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback" style="color:red"><?php echo $Password_err; ?></span>
            </div>
            <div class="btn">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
	</div>
</section>
</body>
</html>