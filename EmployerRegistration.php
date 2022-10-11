<?php
//Copy and pasted the register page, will adjust to deal with employee variables.

// Include config file
require_once "config.php";
session_start();

//add this later if (isset)
// Define variables and initialize with empty values
$CompanyName = $CompanyEmail = $CompanyPhone = $City = $ZipCode = $StreetAddress = $State = $Title = $AddressID = "";
$CompanyName_err = $CompanyEmail_err = $CompanyPhone_err = $City_err = $ZipCode_err = $StreetAddress_err = $Title_err = $State_err = "";
/*INSERT INTO state(StateName)
VALUES
('Alabama'),
('Alaska'),
('Arizona'),
('Arkansas'),
('California'),
('Colorado'),
('Connecticut'),
('Delaware'),
('District of Columbia'),
('Florida'),
('Georgia'),
('Hawaii'),
('Idaho'),
('Illinois'),
('Indiana'),
('Iowa'),
('Kansas'),
('Kentucky'),
('Louisiana'),
('Maine'),
('Maryland'),
('Massachusetts'),
('Michigan'),
('Minnesota'),
('Mississippi'),
('Missouri'),
('Montana'),
('Nebraska'),
('Nevada'),
('New Hampshire'),
('New Jersey'),
('New Mexico'),
('New York'),
('North Carolina'),
('North Dakota'),
('Ohio'),
('Oklahoma'),
('Oregon'),
('Pennsylvania'),
('Rhode Island'),
('South Carolina'),
('South Dakota'),
('Tennessee'),
('Texas'),
('Utah'),
('Vermont'),
('Virginia'),
('Washington'),
('West Virginia'),
('Wisconsin'),
('Wyoming');*/
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    
    if(empty(trim($_POST["CompanyName"])))
    {
        $CompanyName_err = "Please enter your company name.";
    } 
    else
    {
        $CompanyName = trim($_POST["CompanyName"]);
    }

    if(empty(trim($_POST["CompanyEmail"])))
    {
        $CompanyEmail_err = "Please enter your company email.";
    } 
    else
    {
        $CompanyEmail = trim($_POST["CompanyEmail"]);
    }

    if(empty(trim($_POST["CompanyPhone"])))
    {
        $CompanyPhone_err = "Please enter your company's phone number.";
    } 
    else
    {
        $CompanyPhone = trim($_POST["CompanyPhone"]);
    }

    if(empty(trim($_POST["ZipCode"])))
    {
        $ZipCode_err = "Please enter your zipcode.";
    } 
    else
    {
        $ZipCode = trim($_POST["ZipCode"]);
    }

    if(empty(trim($_POST["City"])))
    {
        $City_err = "Please enter your city.";
    } 
    else
    {
        $City = trim($_POST["City"]);
    }

    if(empty(trim($_POST["State"])))
    {
        $State_err = "Please enter your state.";
    } 
    else
    {
        $State = trim($_POST["State"]);
    }

    if(empty(trim($_POST["StreetAddress"])))
    {
        $StreetAddress_err = "Please enter your street address.";
    } 
    else
    {
        $StreetAddress = trim($_POST["StreetAddress"]);
    }

    if(empty(trim($_POST["Title"])))
    {
        $Title_err = "Please enter your company role.";
    } 
    else
    {
        $Title = trim($_POST["Title"]);
    }
 

    // Check input errors before inserting in database
    if(empty($CompanyName_err) && empty($CompanyEmail_err) && empty($CompanyPhone_err) && empty($ZipCode_err) && empty($StreetAddress_err) && empty($City_err) && empty($Title_err))
    {
        
        // Prepare an insert statement
        $sql  = "INSERT INTO zipcodes (ZipCode, City, State) VALUES(?, ?, ?)";
        $sql2 = "INSERT INTO address (StreetAddress, ZipCode) VALUES (?, ?)";
        $sql4 = "INSERT INTO employer (CompanyName, CompanyEmail, CompanyPhone, UserID, AddressID) VALUES (?, ?, ?, ?, ?)";
        $sql5= "INSERT INTO roles (Title) VALUES (?)";

        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_ZipCode, $param_City, $param_State);
            
            // Set parameters
            $param_ZipCode = $ZipCode; 
            $param_City = $City;
            $param_State = $State;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                //Keep going through all prepared statements
            } 
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }
        if($stmt = mysqli_prepare($link, $sql2))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_StreetAddress, $param_ZipCode);
            
            // Set parameters
            $param_StreetAddress = $StreetAddress; 
            $param_ZipCode = $ZipCode;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                //Keep going through all prepared statements
            } 
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }

            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    
        if($stmt = mysqli_prepare($link, $sql4))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_CompanyName, $param_CompanyEmail, $param_CompanyPhone, $param_UserID, $param_AddressID);
            
            // Set parameters
            $param_CompanyName = $CompanyName;
            $param_CompanyEmail = $CompanyEmail; 
            $param_CompanyPhone = $CompanyPhone;
            $param_UserID = $_SESSION["UserID"]; 
            $param_AddressID = $AddressID;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                //Keep going through all prepared statements
            } 
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }
        if($stmt = mysqli_prepare($link, $sql5))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_Title);
            
            // Set parameters
            $param_Title = $Title;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // Redirect to login page
                header("location: welcome.php");
            } 
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

    // Close connection
    mysqli_close($link);
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <Title>Employer Sign Up</Title>
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
        <p>Please fill this form to sign up as an employer.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="CompanyName" class="form-control <?php echo (!empty($CompanyName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $CompanyName; ?>">
                <span class="invalid-feedback"><?php echo $CompanyName_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Company Email</label>
                <input type="text" name="CompanyEmail" class="form-control <?php echo (!empty($CompanyEmail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $CompanyEmail; ?>">
                <span class="invalid-feedback"><?php echo $CompanyEmail_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Company Phone Number</label>
                <input type="text" name="CompanyPhone" class="form-control <?php echo (!empty($CompanyPhone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $CompanyPhone; ?>">
                <span class="invalid-feedback"><?php echo $CompanyPhone_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Street Address</label>
                <input type="text" name="StreetAddress" class="form-control <?php echo (!empty($StreetAddress_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $StreetAddress; ?>">
                <span class="invalid-feedback"><?php echo $StreetAddress_err; ?></span>
            </div>  
            <div class="form-group">
                <label>City</label>
                <input type="text" name="City" class="form-control <?php echo (!empty($City_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $City; ?>">
                <span class="invalid-feedback"><?php echo $City_err; ?></span>
            </div>  
            <div class="form-group">
                <label>Zip Code</label>
                <input type="text" name="ZipCode" class="form-control <?php echo (!empty($ZipCode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ZipCode; ?>">
                <span class="invalid-feedback"><?php echo $ZipCode_err; ?></span>
            </div>    
              <div class="form-group">
                <label>State</label>
                <input type="text" name="State" class="form-control <?php echo (!empty($State_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $State; ?>">
                <span class="invalid-feedback"><?php echo $State_err; ?></span>
            <div class="form-group">
                <label>Role Title</label>
                <input type="text" name="Title" class="form-control <?php echo (!empty($Title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Title; ?>">
                <span class="invalid-feedback"><?php echo $Title_err; ?></span>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Go back. <a href="welcome.php">Welcome page</a>.</p>
        </form>
    </div>
</section>	
</body>
</html>