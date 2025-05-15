<?php 
session_start(); // Starting session

$admin_email = $_POST['adname'];
$admin_pass = $_POST['adpassw'];

include('dbconnect.php');

if(!$con) {  
    die("Failed to connect with MySQL: ". mysqli_connect_error());  
}

function loginChecker($con, $admin_email, $admin_pass) {
    $sql1 = "SELECT * FROM admins WHERE admin_key = '$admin_email' AND passcode ='$admin_pass'";
    $result = mysqli_query($con, $sql1);
    
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $acc_id = $row['admin_id'];
        $acc_key = $row['admin_key'];

        // Set session variables
        $_SESSION['acc_id'] = $acc_id;
        $_SESSION['acc_key'] = $acc_key;
        $_SESSION['acc_type'] = 'admin';

        // Redirect to index.php or any other page after successful login
        header('Location: index.php');
        exit();
    } else {
        // Redirect to login page with error message
        header('Location: login.php?error=info_mismatch');
        exit();
    }
}

if(isset($admin_email, $admin_pass)) {
    loginChecker($con, $admin_email, $admin_pass);
}

mysqli_close($con);
?>
