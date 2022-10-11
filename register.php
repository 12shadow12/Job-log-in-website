<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$FirstName = $LastName = $Phone = $Email = $Password = $confirm_Password = "";
$FirstName_err = $LastName_err = $Phone_err = $Email_err = $Password_err = $confirm_Password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    
    
    // Validate Email
    if(empty(trim($_POST["Email"])))
    {
        $Email_err = "Please enter a Email.";
    } 
    else
    {
        // Prepare a select statement
        $sql = "SELECT UserID FROM useraccount WHERE Email = ?";
        
        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_Email);
            
            // Set parameters
            $param_Email = trim($_POST["Email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $Email_err = "This Email is already taken.";
                } 
                else
                {
                    $Email = trim($_POST["Email"]);
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
    


    // Validate Password
    if(empty(trim($_POST["Password"])))
    {
        $Password_err = "Please enter a Password.";     
    } 
    elseif(strlen(trim($_POST["Password"])) < 7)
    {
        $Password_err = "Password must have at least 8 characters.";
    } 
    else
    {
        $Password = trim($_POST["Password"]);
    }
    
    // Validate confirm Password
    if(empty(trim($_POST["confirm_Password"])))
    {
        $confirm_Password_err = "Please confirm password.";     
    } 
    else
    {
        $confirm_Password = trim($_POST["confirm_Password"]);
        if(empty($Password_err) && ($Password != $confirm_Password))
        {
            $confirm_Password_err = "Password did not match.";
        }
    }

       
    if(empty(trim($_POST["FirstName"])))
    {
        $FirstName_err = "Please enter your first name.";     
    }
    else
    {
        $FirstName = trim($_POST["FirstName"]);
    }

    if(empty(trim($_POST["LastName"])))
    {
        $LastName_err = "Please enter your first name.";     
    }
    else
    {
        $LastName = trim($_POST["LastName"]);
    }

    if(empty(trim($_POST["Phone"])))
    {
        $Phone_err = "Please enter your phone number.";     
    }
    else
    {
        $Phone = trim($_POST["Phone"]);
    }

    // Check input errors before inserting in database
    if(empty($Email_err) && empty($Password_err) && empty($confirm_Password_err) && empty($FirstName_err) && empty($LastName_err) && empty($Phone_err))
    {
        
        // Prepare an insert statement
        $sql = "INSERT INTO useraccount (Email, Password, FirstName, LastName, Phone) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_Email, $param_Password, $param_FirstName, $param_LastName, $param_Phone);
            
            // Set parameters
            $param_Email = $Email;
            $param_Password = Password_hash($Password, PASSWORD_DEFAULT); // Creates a Password hash
            $param_FirstName = $FirstName;
            $param_LastName = $LastName;
            $param_Phone = $Phone;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // Redirect to login page
                header("location: login.php");
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
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
    <div class="textBx">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="FirstName" class="form-control <?php echo (!empty($FirstName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $FirstName; ?>">
                <span class="invalid-feedback"><?php echo $FirstName_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="LastName" class="form-control <?php echo (!empty($LastName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $LastName; ?>">
                <span class="invalid-feedback"><?php echo $LastName_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="Phone" class="form-control <?php echo (!empty($Phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Phone; ?>">
                <span class="invalid-feedback"><?php echo $Phone_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="Email" class="form-control <?php echo (!empty($Email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Email; ?>">
                <span class="invalid-feedback"><?php echo $Email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="Password" class="form-control <?php echo (!empty($Password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Password; ?>">
                <span class="invalid-feedback"><?php echo $Password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_Password" class="form-control <?php echo (!empty($confirm_Password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_Password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_Password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</section>	
</body>
</html>