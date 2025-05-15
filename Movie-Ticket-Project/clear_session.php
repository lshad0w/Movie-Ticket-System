<?php
session_start();

// List of session variables to preserve
$preserve = array('acc_id', 'acc_key', 'acc_type');

// Iterate through all session variables
foreach ($_SESSION as $key => $value) {
    // Check if the current session variable should be preserved
    if (!in_array($key, $preserve)) {
        unset($_SESSION[$key]); // Unset session variable if not in preserve list
    }
}

// Redirect to another page after unsetting session variables
header('Location: index.php');
exit();
?>
