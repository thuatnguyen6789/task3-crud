<?php
// Process delete operation after confirmation
if (isset($_POST["Id"]) && !empty($_POST["Id"]))
{
    // Include config file
    require_once 'config.php';

    // Prepare a select statement
    $sql = "DELETE FROM people WHERE Id = ?";
    if ($stmt = mysqli_prepare($link,$sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt,"i",$param_id);

        // Set parameters
        $param_id = trim($_POST["Id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)){
            // Records delete successfully. Redirect to landing page
            header("location: index.php");
            exit();
        }else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
}else{
    // Check existence of id parameter
    if (empty(trim($_GET["Id"]))){
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
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>Delete Record</h1>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger fade in">
                        <input type="hidden" name="Id" value="<?php echo trim($_GET["Id"]); ?>"/>
                        <p>Are you sure you want to delete this record?</p><br>
                        <p>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="index.php" class="btn btn-default">No</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
