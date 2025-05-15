<?php
session_start(); // Start session

include('database.php');

/*
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
// Generate movie ID

// Function to convert minutes to HH:MM:SS format
function convertMinutesToHHMMSS($minutes) {
    $hours = floor($minutes / 60);
    $minutes = $minutes % 60;
    $seconds = 0; // Assuming the format is HH:MM:SS where SS is always 0 for durations in minutes
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}

// Initialize variables
$movie_id_db = ''; // This will be generated below

// Generate movie ID
$cntmovie_qry = "SELECT COUNT(*) as total FROM movies";
$cntmovie_rslt = mysqli_query($con, $cntmovie_qry);
if (mysqli_num_rows($cntmovie_rslt) > 0) {
    $row = mysqli_fetch_assoc($cntmovie_rslt);
    $total = $row['total'] + 1;
    $counter = str_pad($total, 3, '0', STR_PAD_LEFT);
    $movie_id_db = "Movie_ID_" . $counter;
}

/*
$file_name = $_FILES['adImage']['name'];
$file_tmp = $_FILES['adImage']['tmp_name'];
$file_type = $_FILES['adImage']['type'];
$file_error = $_FILES['adImage']['error'];

$target_dir = "poster/";
$target_file = $target_dir . basename($file_name);

if (move_uploaded_file($file_tmp, $target_file)) {
    echo "<script>alert('Successfully Uploaded');</script>";
    $movie_poster = $file_name; // Use the uploaded file name as the new image
} else {
    echo "<script>alert('Error uploading file.');</script>";
    $movie_poster = ''; // Handle this case as per your needs
}
    */

//to insert new user data
if(isset($_POST['insertnewMovie'])) {
    $movie_title= $_POST['adTitle'];
    $movie_release=$_POST['adRelease'];
    $movie_rated= $_POST['adRated'];
    $movie_duration_in_minutes = intval($_POST['adDuration']); // Assuming adDuration is the input field for minutes
    $movie_time = convertMinutesToHHMMSS($movie_duration_in_minutes);
    $movie_description= $_POST['adDesc'];
    $movie_trailer = $_POST['adTrailer'];

    
        $adCast = $_POST['adCast'];
        $adGenre = $_POST['adGenre'];
    
        // Insert into movies table
        $query2 = "INSERT INTO movies (movie_id, title, year_release, rated, time, description, poster,trailer)
                   VALUES ('$movie_id_db', '$movie_title', '$movie_release', '$movie_rated', '$movie_time',
                           '$movie_description', '$movie_poster','$movie_trailer')";
        mysqli_query($con, $query2);
    
    // Check if movie insertion was successful
    if (mysqli_affected_rows($con) > 0) {
        $movie_id = mysqli_insert_id($con); // Get the auto-generated movie_id from the last insert operation

        // Insert actors
        $actors_array = explode(", ", $adCast);
        foreach ($actors_array as $actor) {
            $insert_actor = "INSERT INTO actors (movie_id, actors) VALUES ('$movie_id', '$actor')";
            if (!$con->query($insert_actor)) {
                echo "Error inserting actor: " . $con->error;
                // Handle error as needed (rollback transaction, show user message, etc.)
            }
        }

        // Insert genres
        $genres_array = explode(", ", $adGenre);
        foreach ($genres_array as $genre) {
            $insert_genre = "INSERT INTO genre (movie_id, genre) VALUES ('$movie_id', '$genre')";
            if (!$con->query($insert_genre)) {
                echo "Error inserting genre: " . $con->error;
                // Handle error as needed (rollback transaction, show user message, etc.)
            }
        }

        // Redirect to movie menu or any other page after successful insert
        header('Location: moviemenu.php');
        exit();
    } else {
        echo "Error inserting movie: " . $con->error;
        // Handle error as needed (rollback transaction, show user message, etc.)
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ticket buying login page</title>
    <link rel="stylesheet" href="adminpop.css">
    <style>
        body {
            background-color: white;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-size: 18px;
        }
        .container {
            margin-left: 50px;
            margin-top: 20px;
        }
        .createbtn {
            width: 200px;
            padding: 10px;
            margin-top: 10px;
        }
        form {
            width: 50%;
        }
        label {
            font-size: 18px;
        }
        button {
            width: 200%;
            padding: 20px;
        }
        input[type="datetime-local"], select {
            width: 40%;
      padding: 12px 20px;
      margin: 8px 0;
      display: inline-block;

      background-color: white;
      border: none;
      border-bottom: 1px solid black;
      box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="sidenav"> 
        <h1>Admin Editor Page</h1>
        <hr>
        <form action="usermenu.php" method="post">
            <button type="button" onclick="window.location.href = 'usermenu.php'">Users</button>
        </form>
        <form action="moviemenu.php" method="post">
            <button type="button" onclick="window.location.href = 'moviemenu.php'">Movies</button>
        </form>
        <form action="showtimesmenu.php" method="post">
            <button type="button" onclick="window.location.href = 'showtimesmenu.php'">Showtimes</button>
        </form>
        <form action="index.php" method="post">
            <button type="button" onclick="window.location.href = 'index.php'">Return</button>
        </form>
    </div>

    <div class="box1"> 
        <div class="container">
            <form action="" method="post" autocomplete="off">  
                <label><b>Movie ID:</b></label><br>
                <input type="text" value="<?php echo $movie_id_db ?>" name="adId" id="adId" readonly><br>

                <label><b>Title</b></label><br>
                <input type="text" placeholder="Enter Movie Title" name="adTitle" id="adTitle" required><br>

                <label><b>Release Date</b></label><br>
                <input type="text" placeholder="Enter Release Year (e.g., 2024)" name="adRelease" id="adRelease" required><br>

                <label><b>Rated</b></label><br>
                <input type="text" placeholder="Enter Rated (e.g., G, PG, SPG)" name="adRated" id="adRated" required><br>

                <label><b>Time</b></label><br>
                <input type="text" placeholder="Enter Show Time" name="adTime" id="adTime" required><br>

                <label><b>Description</b></label><br>
                <input type="text" placeholder="Enter Movie Description" name="adDesc" id="adDesc" required><br>

                <label><b>Cast</b></label><br>
                  <input type="text" autocomplete="off"  placeholder="Enter Cast" name="adCast" id="adCast" ><br>
                  
                <label><b>Genre</b></label><br>
                <input type="text" autocomplete="off"  placeholder="Enter Genre" name="adGenre" id="adGenre" ><br>

                <label><b>Upload Image</b></label><br>
                <input type="file" name="adImage" id="adImage" ><br>

                <label><b>Upload trailer</b></label><br>
                <input type="file" name="adTrailer" id="adTrailer" ><br>

                <div class="container">
                    <input class="createbtn" type="submit" value="Insert New Movie" name="insertnewMovie" id="insertnewMovie">
                    <input class="createbtn" type="button" value="Return" onclick="window.location='moviemenu.php'">
                </div>
            </form>
        </div>
    </div>

</body>
</html>
