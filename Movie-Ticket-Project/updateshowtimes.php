<?php 
session_start(); // Start session

include('dbconnect.php');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$acc_id = $_SESSION['acc_id'];
$acc_key = $_SESSION['acc_key'];
$acc_type = $_SESSION['acc_type'];

if (!isset($_SESSION['acc_id']) || !isset($_SESSION['acc_key']) || !isset($_SESSION['acc_type'])) {
    // Redirect to login page if session variables are not set
    header('Location: login.php');
    exit();
}

if ($acc_type == 'user') {
    header('Location: index.php');
    exit();
}

$usremail = "";
$usrpass = "";

// Check if useracc_id is set in the URL
if (isset($_GET['showtime_id'])) {
    $id = $_GET['showtime_id'];

    // Select query to fetch user details
    $select = "SELECT * FROM showtimes WHERE showtime_id='$id'";
    $data = mysqli_query($con, $select);

    // Check if data was fetched successfully
    if ($data) {
        $row = mysqli_fetch_array($data);

        // Assign values from database to variables
        $showtime = $row['showtime'];
        $showtime_id = $row['showtime_id'];
    } else {
        echo "Error fetching user data: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Account Update</title>
    <link rel="stylesheet" href="adminpop.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
        .container {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        input[type=datetime-local], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .createbtn, .returnbtn {
            width: 30%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        .returnbtn {
            background-color: #f44336;
        }
        .createbtn:hover, .returnbtn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form class="modal-content" name="Formation" id="Formation" method="post" action="">
        <div class="imgcontainer">
            <!-- Image container if needed -->
        </div>
        <div class="container">
        <label for="movie">Movie</label>
        <select name="movie" id="movie">
                    <?php 
                    $selectMovies = "SELECT * FROM movies;";
                    $queryMovies = mysqli_query($con, $selectMovies);

                    if (mysqli_num_rows($queryMovies) > 0) {
                        while ($row = mysqli_fetch_assoc($queryMovies)) {
                            $movie_id = htmlspecialchars($row['movie_id']);
                            $movie_title = htmlspecialchars($row['title']);
                            echo '<option value="' . $movie_id . '">' . $movie_title . '</option>';
                        }
                    }
                    ?>
        </select>
        </div>
        <div class="container">
            <label for="newdate"><b>Date</b></label>
            <input type="datetime-local" value="<?php echo $showtime ?>" name="newdate" id="newdate" autocomplete="off">
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <input type="submit" class="createbtn" name="update_btn" value="Update">
            <input type="button" class="returnbtn" value="Return" onclick="window.history.back();">
        </div>
    </form>

    <?php
    if(isset($_POST['update_btn'])) {
        $new_date = $_POST['newdate'];
        $new_id = $_POST['movie'];

        // Update query to update user details
        $update = "UPDATE showtimes SET showtime='$new_date', movie_id='$new_id' WHERE showtime_id='$id'";
        $data = mysqli_query($con, $update);

        if($data) {
            echo "<script>alert('User account updated successfully');</script>";
            echo "<script>window.location.href='showtimesmenu.php';</script>"; // Redirect to previous page after update
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    }
    ?>

</body>
</html>
