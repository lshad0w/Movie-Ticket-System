<?php
    include('database.php');
	session_start();
	// Unset all session variables
	$_SESSION = array();
	session_destroy();
	echo "<script>alert('Successfully Logged out');</script>";
	header('location:login.php');



	/*lagay lang dito incase na gusto natin lagyan ng cookies yung website
	setcookie(session_name(), '', time() - 3600, '/');

	
	if (isset($_COOKIE["userAccount"]) AND isset($_COOKIE["pass"])){
		setcookie("userAccount", '', time() - (3600));
		setcookie("pass", '', time() - (3600));
	}
*/
 
?>


