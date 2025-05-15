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

// Insert new showtime data into database
if (isset($_POST['insertShowtime'])) {
    $datetime = $_POST['datetime'];
    $movie = $_POST['movie'];

    // Fetch movie duration from the movies table and convert to minutes
    $duration_query = "SELECT `time` FROM movies WHERE movie_id = '$movie'";
    $duration_result = mysqli_query($con, $duration_query);

    if ($duration_result && mysqli_num_rows($duration_result) > 0) {
        $row = mysqli_fetch_assoc($duration_result);
        $movie_time = $row['time'];
        
        // Convert HH:MM:SS to minutes
        list($hours, $minutes, $seconds) = explode(':', $movie_time);
        $movie_duration_minutes = ($hours * 60) + $minutes;

        // Convert the datetime to a MySQL compatible format
        $datetimeFormatted = date('Y-m-d H:i:s', strtotime($datetime));
        $datetimeAfterDuration = date('Y-m-d H:i:s', strtotime("$datetimeFormatted + $movie_duration_minutes minutes"));

        // Check if there is an overlapping showtime for the selected movie within the movie duration
        $check_query = "
            SELECT COUNT(*) AS count 
            FROM showtimes 
            WHERE movie_id = '$movie' 
            AND (
                (showtime >= '$datetimeFormatted' AND showtime < '$datetimeAfterDuration') OR 
                ('$datetimeFormatted' >= showtime AND '$datetimeFormatted' < DATE_ADD(showtime, INTERVAL $movie_duration_minutes MINUTE))
            )";

        $check_result = mysqli_query($con, $check_query);

        if ($check_result) {
            $row = mysqli_fetch_assoc($check_result);
            $count = $row['count'];

            if ($count > 0) {
                // Conflict exists within the movie duration
                echo "<script>alert('Error: There is already a showtime for the selected movie within the movie duration.'); window.location.href='showtimesmenu.php';</script>";
            } else {
                // Insert query with proper datetime conversion
                $insert_query = "INSERT INTO showtimes (movie_id, showtime) 
                                 VALUES ('$movie', STR_TO_DATE('$datetime', '%Y-%m-%dT%H:%i'))";

                if (mysqli_query($con, $insert_query)) {
                    // Redirect to clear POST data after insertion
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    // Handle any potential errors during insertion
                    echo "Error: " . $insert_query . "<br>" . mysqli_error($con);
                }
            }
        } else {
            // Handle query execution error
            echo "Error checking datetime existence: " . mysqli_error($con);
        }
    } else {
        // Handle error fetching movie duration
        echo "Error fetching movie duration: " . mysqli_error($con);
    }
}


// Delete showtime from table
if (isset($_GET['showtime_id'])) {
    $showtime_id = $_GET['showtime_id'];
    
    // Check if there are any reservations for the showtime before deletion
    $check_reservations = "SELECT COUNT(*) as reservations FROM reservation WHERE showtime_id = '$showtime_id'";
    $reservations_result = mysqli_query($con, $check_reservations);
    $reservations_count = mysqli_fetch_assoc($reservations_result)['reservations'];

    if ($reservations_count > 0) {
        echo "<script>alert('Cannot delete showtime with existing reservations.'); window.location.href='showtimesmenu.php';</script>";
    } else {
        $delete = mysqli_query($con, "DELETE FROM `showtimes` WHERE `showtime_id` = '$showtime_id'");
        if ($delete) {
            echo "<script>alert('Showtime deleted successfully.'); window.location.href='showtimesmenu.php';</script>";
        } else {
            echo "<script>alert('Error deleting showtime.'); window.location.href='showtimesmenu.php';</script>";
        }
    }
}

// Retrieve all showtimes from database
$selectShowtimes = 
    "SELECT 
        showtimes.showtime_id, 
        showtimes.movie_id, 
        showtimes.showtime, 
        movies.title,
        movies.time,
        (SELECT COUNT(*) FROM reservation WHERE showtime_id = showtimes.showtime_id) as reservations
    FROM 
        showtimes 
    INNER JOIN 
        movies 
    ON 
        showtimes.movie_id = movies.movie_id
    ORDER BY showtimes.showtime ASC
    ";

$query = mysqli_query($con, $selectShowtimes);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Showtime Editor</title>
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
        <div class="containertop"><h1>Showtime Editor</h1></div>
        <div class="container">
            <table>
                <tr>
                    <th>Movie</th>
                    <th>Showtime</th>
                    <th colspan="2">Actions</th>
                </tr>
                <?php
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        $showtimeid = htmlspecialchars($row['showtime_id']);
                        $movie_title = htmlspecialchars($row['title']);
                        $showtime = htmlspecialchars($row['showtime']);
                        
                        $formattedTime = date('Y-m-d h:i:s A', strtotime($showtime));
                        $reservations = $row['reservations'];
                ?>
                        <tr>
                            <td><?php echo $movie_title; ?></td>
                            <td><?php echo $formattedTime; ?></td>
                            <td><a href='updateshowtimes.php?showtime_id=<?php echo $showtimeid ?>' class='edtbtn'><img src='img/pen.png' alt='edit' width='40px'></a></td>
                            <?php if ($reservations > 0) { ?>
                                <td><img src='img/remove_disabled.png' alt='delete' width='40px'></td>
                            <?php } else { ?>
                                <td><a href='showtimesmenu.php?showtime_id=<?php echo $showtimeid ?>' class='delbtn'><img src='img/remove.png' alt='delete' width='40px'></a></td>
                            <?php } ?>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>No showtimes found.</td></tr>";
                }
                ?>
            </table>
        </div>
        <div class="container">
            <form action="showtimesmenu.php" method="post" autocomplete="off">
                <div class="showtime">
                    <label for="movie"><b>Movie</b></label><br>
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
                    </select><br>
                    <label for="datetime"><b>Date</b></label><br>
                    <input type="datetime-local" id="datetime" name="datetime"><br>
                    <div class="container">
                        <button value="Insert new Showtime" name="insertShowtime" id="insertShowtime" class="insertShowtime"><img src="img/plus.png" alt="add" width="40px"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
