<?php 
session_start(); // Start session

include('dbconnect.php');

// Check database connection
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

// Retrieve movie details based on movie_id from GET parameter
if (isset($_GET['movie_id'])) {
    $id = $_GET['movie_id'];
    $select = "SELECT * FROM movies WHERE movie_id='$id'";
    $result = mysqli_query($con, $select);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['title'];
        $yrRelease = $row['year_release'];
        $rated = $row['rated'];
        $time = $row['time'];
        $desc = $row['description'];
        $img = $row['poster'];
    } else {
        echo "No movie found with ID: " . $id;
        exit();
    }
} else {
    echo "Movie ID not specified.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movie Update Form</title>
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

input[type=time], input[type=number], input[type=text], input[type=file], textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
}

.imgcontainer {
    text-align: center;
    margin-bottom: 15px;
}

img {
    max-width: 100%;
    height: auto;
}

.createbtn, .returnbtn {
    width: 45%;
    padding: 10px;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.createbtn {
    background-color: #4CAF50;
    color: white;
}

.createbtn:hover {
    background-color: #45a049;
}

.returnbtn {
    background-color: #f44336;
    color: white;
}

.returnbtn:hover {
    background-color: #da190b;
}
    </style>
</head>
<body>
    <form class="modal-content" name="Formation" id="Formation" method="post" enctype="multipart/form-data">
        <div class="container">
            <label for="newId"><b>ID</b></label>
            <input type="text" autocomplete="off" value="<?php echo $id ?>" name="newId" id="newId" readonly>
        </div>

        <div class="container">
            <label for="newTitle"><b>Title</b></label>
            <input type="text" autocomplete="off" value="<?php echo $title ?>" name="newTitle" id="newTitle">
        </div>

        <div class="container">
            <label for="newRelease"><b>Release Date</b></label>
            <input type="number" autocomplete="off" value="<?php echo $yrRelease ?>" name="newRelease" id="newRelease">
        </div>

        <div class="container">
            <label for="newRated"><b>Rated</b></label>
            <input type="text" autocomplete="off" value="<?php echo $rated ?>" name="newRated" id="newRated">
        </div>

        <div class="container">
            <label for="newTime"><b>Time</b></label>
            <input type="text" autocomplete="off" value="<?php echo $time ?>" name="newTime" id="newTime">
        </div>

        <div class="container">
            <label for="newDesc"><b>Description</b></label>
            <textarea name="newDesc" id="newDesc" rows="10" cols="75"><?php echo $desc ?></textarea>
        </div>

        <div class="container">
            <label for="newImage"><b>Upload Image</b></label>
            <input type="file" name="newImage" id="newImage">
        </div>

        <div class="imgcontainer">
            <label>Current Poster:</label><br>
            <img src="<?php echo "poster/" . $img; ?>" alt="Current Poster">
        </div>

        <div class="container">
            <input type="submit" class="createbtn" value="Update Movie" name="updatemovie_btn">
            <input type="button" class="returnbtn" value="Return" onclick="window.location.href = 'moviemenu.php'">
        </div>
    </form>

    <?php 
    // Process form submission to update movie details
    if(isset($_POST['updatemovie_btn'])) {
        // Gather updated values from form inputs
        $newmovie_title = $_POST['newTitle'];
        $newmovie_release = $_POST['newRelease'];
        $newmovie_rated = $_POST['newRated'];
        $newmovie_time = $_POST['newTime'];
        $newmovie_description = $_POST['newDesc'];
        
        // Handle image upload if a new image is selected
        if ($_FILES['newImage']['name'] != '') {
            $newmovie_poster = $_FILES['newImage']['name'];
            $target_dir = "poster/";
            $target_file = $target_dir . basename($_FILES["newImage"]["name"]);
            move_uploaded_file($_FILES["newImage"]["tmp_name"], $target_file);
        } else {
            $newmovie_poster = $img; // Keep the current image if no new one is uploaded
        }

        // Update query to modify movie details in database
        $update = "UPDATE movies SET title='$newmovie_title', year_release='$newmovie_release', rated='$newmovie_rated', time='$newmovie_time', description='$newmovie_description', poster='$newmovie_poster' WHERE movie_id='$id'";
        if (mysqli_query($con, $update)) {
            // Redirect back to previous page after update
            header("Location: moviemenu.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    }
    ?>

</body>
</html>
