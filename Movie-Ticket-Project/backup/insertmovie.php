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

// Generate movie ID
$cntmovie_qry = "SELECT COUNT(*) as total FROM movies";
$cntmovie_rslt = mysqli_query($con, $cntmovie_qry);
if(mysqli_num_rows($cntmovie_rslt) > 0){
    $row = mysqli_fetch_assoc($cntmovie_rslt);
    $total = $row['total'];
    $total++;
    $counter = str_pad($total, 3, '0', STR_PAD_LEFT);
    $movie_id_db = "Movie_ID_" . $counter;
}

// Insert new movie data into database
if(isset($_POST['insertnewMovie'])) {
    $movie_title = $_POST['adTitle'];
    $movie_release = $_POST['adRelease'];
    $movie_rated = $_POST['adRated'];
    $movie_time = $_POST['adTime'];
    $movie_description = $_POST['adDesc'];
    // Assuming you have a field for storing the image path in your database schema
    $movie_poster = $_POST['adImage'];

    $insert_query = "INSERT INTO movies (movie_id, title, year_release, rated, time, description, poster) 
                     VALUES ('$movie_id_db', '$movie_title', '$movie_release', '$movie_rated', '$movie_time', '$movie_description', '$movie_poster')";

    if(mysqli_query($con, $insert_query)) {
        // Redirect to clear POST data after insertion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Handle any potential errors during insertion
        echo "Error: " . $insert_query . "<br>" . mysqli_error($con);
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

                <label><b>Upload Image</b></label><br>
                <input type="file" name="adImage" id="adImage" required><br><br>

                <div class="container">
                    <input class="createbtn" type="submit" value="Insert New Movie" name="insertnewMovie" id="insertnewMovie">
                    <input class="createbtn" type="button" value="Return" onclick="window.location='moviemenu.php'">
                </div>
            </form>
        </div>
    </div>

</body>
</html>
