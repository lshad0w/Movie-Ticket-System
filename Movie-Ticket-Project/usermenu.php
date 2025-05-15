<?php
// Start PHP session
session_start();

// Include database connection
include('dbconnect.php');

// Check database connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Store POST values in session variables
$acc_id = $_SESSION['acc_id'];
$acc_key = $_SESSION['acc_key'];
$acc_type = $_SESSION['acc_type'];

// Validate session existence for security
if (!isset($_SESSION['acc_id']) || !isset($_SESSION['acc_key']) || !isset($_SESSION['acc_type'])) {
    header('Location: login.php'); // Redirect to login page if session variables are not set
    exit();
}

if ($acc_type == 'user') {
    header('Location: index.php');
    exit();
}

// Query to count total users
$cntuser_qry = "SELECT COUNT(*) as total FROM users";
$cntuser_rslt = mysqli_query($con, $cntuser_qry);

// Initialize user ID counter
$total = 0;
if (mysqli_num_rows($cntuser_rslt) > 0) {
    $row = mysqli_fetch_assoc($cntuser_rslt);
    $total = $row['total'] + 1;
}

// Insert new user data into database
if (isset($_POST['createUser'])) {
    $user_email = $_POST['email2'];
    $user_pass = $_POST['pass2'];
    
    // Check if email already exists
    $check_email_query = "SELECT COUNT(*) AS count FROM users WHERE email = '$user_email'";
    $check_email_result = mysqli_query($con, $check_email_query);
    $row = mysqli_fetch_assoc($check_email_result);

    if ($row['count'] > 0) {
        // Email already exists
        echo "<script>alert('Error: Email $user_email is already in use.'); window.location.href='usermenu.php';</script>";
    } else {
        // Insert query without specifying user_id (let the database auto-increment handle it)
        $query2 = "INSERT INTO users (email, passcode) VALUES ('$user_email', '$user_pass')";

        if (mysqli_query($con, $query2)) {
            // Redirect to clear POST data after insertion
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            // Handle any potential errors during insertion
            echo "Error: " . $query2 . "<br>" . mysqli_error($con);
        }
    }
}

// Delete user data from table
if (isset($_GET['useracc_id'])) {
    $usr_id = $_GET['useracc_id'];
    $delete = mysqli_query($con, "DELETE FROM `users` WHERE `user_id` = '$usr_id'");
}

// Retrieve all users from database
$select = "SELECT * FROM users";
$query = mysqli_query($con, $select);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Editor</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="sidenav">
        <a href="index.php" class="return">🏠︎</a>
        <a href="usermenu.php" class="users">Users</a>
        <a href="moviemenu.php" class="movies">Movies</a>
        <a href="showtimesmenu.php" class="showtimes">Showtimes</a> 
    </div>

    <div class="box1"> 
        <div class="containertop"><h1>User Editor</h1></div>
        <div class="container">
            <table>
                <tr>
                    <th>Email</th>
                    <th>Password</th>
                    <th colspan="2">Actions</th>
                </tr>
                <?php
                if (mysqli_num_rows($query) > 0){
                    while($result = mysqli_fetch_assoc($query)){
                ?>
                        <tr>
                            <td><?php echo $result['email'] ?></td>
                            <td><?php echo $result['passcode'] ?></td>
                            <td><a href='updateuser.php?useracc_id=<?php echo $result['user_id'] ?>' class='edtbtn'><img src='img/pen.png' alt='edit' width='40px'></a></td>
                            <td><a href='usermenu.php?useracc_id=<?php echo $result['user_id'] ?>' class='delbtn'><img src='img/remove.png' alt='delete' width='40px'></a></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found.</td></tr>";
                }
                ?>
            </table>
        </div>
        <div class="container">
            <div class="user">
                <form action="usermenu.php" method="post" autocomplete="off">
                    <label for="email2"><b>Email</b></label><br>
                    <input type="text" placeholder="Assign Email" name="email2" id="email2" autocomplete="off" required><br>
                    <label for="pass2"><b>Password</b></label><br>
                    <input type="text" placeholder="Assign Password" name="pass2" id="pass2" autocomplete="off" required><br>
                    <div class="container">
                        <button value="Create New Account" name="createUser" id="createUser" class="adduser"><img src="img/plus.png" alt="add" width="40px"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
