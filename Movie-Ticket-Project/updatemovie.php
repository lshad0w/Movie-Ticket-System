<?php 
session_start(); // Start session

include('database.php');

/*
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
*/

$id = $_GET['movie_id'];
$select="SELECT * FROM movies WHERE movie_id='$id'";
$data=$con->query($select);
$row=$data->fetch_assoc();

//para ma echo sa text field yung value na eedit mo
$title = "";
$yrRelease = "";
$rated = "";
$time = "";
$desc = "";
$img = "";
$vid = "";

//Fetch Actors
$select_actors = "SELECT * FROM actors WHERE movie_id='$id'";
$data_actors = $con->query($select_actors);
    $actors = [];
    if ($data_actors->num_rows > 0) {
        while ($row_actor = $data_actors->fetch_assoc()) {
            $actors[] = $row_actor['actors'];
        }
        $cast = implode(", ", $actors);
    } else {
        $cast = 'No actors available';
    }

// Fetch genres for the movie
$select_genre = "SELECT * FROM genre WHERE movie_id='$id'";
$data_genre = $con->query($select_genre);
    $genres = [];
    if ($data_genre->num_rows > 0) {
        while ($row_genre = $data_genre->fetch_assoc()) {
            $genres[] = $row_genre['genre'];
        }
        $genre = implode(", ", $genres);
    } else {
        $genre = 'No genres available';
    }

//same dito para maecho sa text field
//need talaga andito sya kasi di siya mag didisplay ng maayos
$title = $row['title'];
$yrRelease = $row['year_release'];
$rated = $row['rated'];
$time = $row['time'];
$desc = $row['description'];
$img = $row['poster'];
$vid = $row['trailer'];


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
                    <label><b>Cast (comma-separated)</b></label><br>
                  <input type="text" autocomplete="off"  value="<?php echo $cast ?>" name="newCast" id="newCast" /><br>
        </div>  
                  
        <div class="container">
                <label><b>Genre (comma-separated)</b></label><br>
                <input type="text" autocomplete="off"  value="<?php echo $genre ?>" name="newGenre" id="newGenre" /><br>
        </div>  
        
        <div class="container">
            <label for="newImage"><b>Upload Image</b></label>
            <input type="file" name="newImage" id="newImage">
        </div>

        <div class="imgcontainer">
            <label>Current Poster:</label><br>
            <?php  if (!empty($img)) { ?>
                <img src="<?php echo "image/" .$img; ?>"> <!-- adjust mo lang uli yung image/ kung saan naka store yung media -->
                <?php } else {
                 echo "No image";
                }  ?>
        </div>

        <div class="container">
            <label for="newImage"><b>Upload Trailer</b></label>
            <input type="file" name="newTrailer" id="newTrailer">
        </div>

        <div class="imgcontainer">
            <label>Current Trailer:</label><br>
            <?php  if (!empty($vid)) { ?>
                <video width="320" height="240" controls>
                <source src="<?php echo "image/" .$vid ?>" type="video/mp4">  <!-- adjust mo lang uli yung image/ -->
                </video>
                <?php } else {
                echo "No trailer";
            }   ?>
        </div>

        <div class="container">
            <input type="submit" class="createbtn" value="Update Movie" name="updatemovie_btn">
            <input type="button" class="returnbtn" value="Return" onclick="window.location.href = 'moviemenu.php'">
        </div>
    </form>

    <?php 
// Handle form submission
if (isset($_POST['updatemovie_btn'])) {
    $newmovie_title = $_POST['newTitle'];
    $newmovie_release = $_POST['newRelease'];
    $newmovie_rated = $_POST['newRated'];
    $newmovie_time = $_POST['newTime'];
    $newmovie_description = $_POST['newDesc'];
    $newCast = $_POST['newCast'];
    $newGenre = $_POST['newGenre'];

    //Handle image upload
    if ($_FILES['newImage']['size'] > 0) {
        $file_name = $_FILES['newImage']['name'];
        $file_tmp = $_FILES['newImage']['tmp_name'];
        $file_type = $_FILES['newImage']['type'];
        $file_error = $_FILES['newImage']['error'];

        // Move uploaded file to destination directory
        $target_dir = "image/";
        $target_file = $target_dir . basename($file_name);

        if (move_uploaded_file($file_tmp, $target_file)) {
            echo "<script>alert('Successfully Uploaded');</script>";
            $oldImage = $file_name; // Use the uploaded file name as the new image
        } else {
            echo "<script>alert('Error uploading file.');</script>";
            $oldImage = $img; // Use the existing image if upload fails
        }
    } else {
        // No new image uploaded, retain the original image filename
        $oldImage = $img; // Assuming $img is already defined earlier
    }

        //Handle trailer upload
        if ($_FILES['newTrailer']['size'] > 0) {
            $file_name = $_FILES['newTrailer']['name'];
            $file_tmp = $_FILES['newTrailer']['tmp_name'];
            $file_type = $_FILES['newTrailer']['type'];
            $file_error = $_FILES['newTrailer']['error'];
    
            // Move uploaded file to destination directory
            $target_dir = "image/";
            $target_file = $target_dir . basename($file_name);
    
            if (move_uploaded_file($file_tmp, $target_file)) {
                echo "<script>alert('Successfully Uploaded');</script>";
                $oldTrailer = $file_name; // Use the uploaded file name as the new trailer
            } else {
                echo "<script>alert('Error uploading file.');</script>";
                $oldTrailer = $vid; // Use the existing trailer if upload fails
            }
        } else {
            // No new image uploaded, retain the original Trailer filename
            $oldTrailer = $vid; // Assuming $trailer is already defined earlier
        }


        

    // Update movie details in database
    $update_movie = "UPDATE movies SET title='$newmovie_title', year_release='$newmovie_release', 
                    rated='$newmovie_rated', time='$newmovie_time', description='$newmovie_description', 
                    poster='$oldImage',trailer='$oldTrailer' WHERE movie_id='$id'";
    $data_movie = mysqli_query($con, $update_movie);

    // Update actors
    // First delete existing actors for this movie
    $delete_actors = "DELETE FROM actors WHERE movie_id='$id'";
    $con->query($delete_actors);

    // Insert new actors
    $actorsArray = explode(', ', $newCast);
    foreach ($actorsArray as $actor) {
        $actor = trim($actor);
        if (!empty($actor)) {
            $insert_actor = "INSERT INTO actors (movie_id, actors) VALUES ('$id', '$actor')";
            $con->query($insert_actor);
        }
    }

    // Update genres
    // First delete existing genres for this movie
    $delete_genres = "DELETE FROM genre WHERE movie_id='$id'";
    $con->query($delete_genres);

    // Insert new genres
    $genresArray = explode(', ', $newGenre);
    foreach ($genresArray as $genre) {
        $genre = trim($genre);
        if (!empty($genre)) {
            $insert_genre = "INSERT INTO genre (movie_id, genre) VALUES ('$id', '$genre')";
            $con->query($insert_genre);
        }
    }

    // Redirect to movie menu or any other page after update
    header('Location: moviemenu.php');
    exit();
}
   ?>    

</body>
</html>
