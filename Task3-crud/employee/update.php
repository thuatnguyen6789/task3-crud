<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$Name = $Tel = $Address = $F = $Location = $start_date = $end_date = "";
$Name_err = $Tel_err = $Address_err = $F_err = $Location_err = $start_date_err = $end_date_err = "";

// Processing form data when form is submitted
if (isset($_POST["Id"]) && !empty($_POST["Id"])){
    // Get hidden input value
    $Id = $_POST["Id"];

    // Validate name
    $input_Name = trim($_POST["Name"]);
    if (empty($input_Name)){
        $Name_err = "Please enter a name.";
    }elseif (!filter_var(trim($_POST["Name"]), FILTER_VALIDATE_REGEXP,
        array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $Name_err = 'Please enter a valid name.';
    }else{
        $Name = $input_Name;
    }

    // Validate Tel
    $input_Tel = trim($_POST["Tel"]);
    if (empty($input_Tel)){
        $Tel_err = 'Please enter an Tel';
    }else{
        $Tel = $input_Tel;
    }


    // Validate address
    $input_Address = trim($_POST["Address"]);
    if (empty($input_Address)){
        $Address_err = 'Please enter an address.';
    }else{
        $Address = $input_Address;
    }

    // Validate F
    $input_F = trim($_POST["F"]);
    if (empty($input_F)){
        $F_err = 'Please enter an F.';
    }else{
        $F = $input_F;
    }

    // Validate Location
    $input_Location = trim($_POST["Location"]);
    if (empty($input_Location)){
        $Location_err = 'Please enter an Location.';
    }else{
        $Location = $input_Location;
    }

    // Validate start_date
    $input_start_date = trim($_POST["start_date"]);
    if (empty($input_start_date)){
        $start_date_err = 'Please enter a start_date';
    }else{
        $start_date = $input_start_date;
    }

    // Validate start_date
    $input_end_date = trim($_POST["end_date"]);
    if (empty($input_end_date)){
        $end_date_err = 'Please enter a start_date';
    }else{
        $end_date = $input_end_date;
    }


    // Check input errors before inserting in database
    if (empty($Name_err) && empty($Tel_err) && empty($Address_err) && empty($F_err) && empty($Location_err) && empty($start_date_err) && empty($end_date_err))
    {
        // Prepare an insert statement
        $sql = "UPDATE people SET Name=?, Tel=?, Address=?, F=?, Location=?, start_date=?, end_date=? WHERE Id=?";


        if ($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt,"sssisssi",$param_Name,$param_Tel, $param_Address, $param_F, $param_Location, $param_start_date, $param_end_date, $param_Id);

            // Set parameters
            $param_Name = $Name;
            $param_Tel = $Tel;
            $param_Address = $Address;
            $param_F = $F;
            $param_Location = $Location;
            $param_start_date = $start_date;
            $param_end_date = $end_date;
            $param_Id = $Id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)){
                // Records update successfully. Redirect to landing page
                header("location: index.php");
                exit();
            }else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($link);
}else{
    // Check existence of id parameter before processing further
    if (isset($_GET["Id"]) && !empty(trim($_GET["Id"]))){
        // Get URL parameter
        $Id = trim($_GET["Id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM people WHERE Id = ?";
        if ($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt,"i",$param_Id);

            //Set parameters
            $param_Id = $Id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $Name = $row["Name"];
                    $Tel = $row["Tel"];
                    $Address = $row["Address"];
                    $F = $row["F"];
                    $Location = $row["Location"];
                    $start_date = $row["start_date"];
                    $end_date = $row["end_date"];
                }else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        //Close connection
        mysqli_close($link);
    }else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width : 1400px;
            margin:  0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Update Record</h2>
                </div>
                <p>Please edit the input values and submit to update the record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($Name_err))? 'has-error' : '' ; ?>">
                        <lable>Name</lable>
                        <input type="text" name="Name" class="form-control" value="<?php echo $Name; ?>">
                        <span class="help-block"><?php echo $Name_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($Tel_err))? 'has-error' : '' ; ?>">
                        <lable>Tel</lable>
                        <input type="text" name="Tel" class="form-control" value="<?php echo $Tel; ?>">
                        <span class="help-block"><?php echo $Tel_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($Address_err))? 'has-error' : '' ; ?>">
                        <lable>Address</lable>
                        <textarea name="Address" class="form-control"><?php echo $Address; ?></textarea>
                        <span class="help-block"><?php echo $Address_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($F_err))? 'has-error' : '' ; ?>">
                        <lable>F</lable>
                        <input type="text" name="F" class="form-control" value="<?php echo $F; ?>">
                        <span class="help-block"><?php echo $F_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($Location_err))? 'has-error' : '' ; ?>">
                        <lable>Location</lable>
                        <input type="text" name="Location" class="form-control" value="<?php echo $Location; ?>">
                        <span class="help-block"><?php echo $Location_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($start_date_err))? 'has-error' : '' ; ?>">
                        <lable>start_date</lable>
                        <input type="text" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                        <span class="help-block"><?php echo $start_date_err;?></span>
                    </div

                    <div class="form-group <?php echo (!empty($end_date_err))? 'has-error' : '' ; ?>">
                        <lable>end_date</lable>
                        <input type="text" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                        <span class="help-block"><?php echo $end_date_err;?></span>
                    </div>

                    <input type="hidden" name="Id" value="<?php echo $Id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
