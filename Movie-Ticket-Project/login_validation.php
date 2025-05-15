<?php 
session_start(); // Start session

$user_email = $_POST['email'];
$user_pass = $_POST['passw'];

include('dbconnect.php');

if (!$con) {  
    die("Failed to connect with MySQL: " . mysqli_connect_error());  
}

function loginChecker($con, $user_email, $user_pass) {
    $sql = "SELECT * FROM users WHERE email = '$user_email' AND passcode = '$user_pass'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $acc_id = $row['user_id'];
        $acc_key = $row['email'];
        
        // Store user information in session variables
        $_SESSION['acc_id'] = $acc_id;
        $_SESSION['acc_key'] = $acc_key;
        $_SESSION['acc_type'] = 'user'; // Assuming 'user' as the account type

        // Redirect to index.php after setting session variables
        header('Location: index.php');
        exit();
    } else {
        // Set error message in session
        $_SESSION['login_error'] = "Invalid email or password";
        header('Location: login.php?error=info_mismatch');
        exit();
    }
}

if (isset($user_email, $user_pass)) {
    loginChecker($con, $user_email, $user_pass);
}

mysqli_close($con);
?>
