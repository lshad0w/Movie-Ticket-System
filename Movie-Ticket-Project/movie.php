<?php 
session_start(); // Starting session

include('dbconnect.php');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Validate session variables
if (!isset($_SESSION['acc_id']) || !isset($_SESSION['acc_key']) || !isset($_SESSION['acc_type'])) {
    header('Location: login.php');
    exit();
}

$acc_id = $_SESSION['acc_id'];
$acc_key = $_SESSION['acc_key'];
$acc_type = $_SESSION['acc_type'];

$sqlMovies = "";
$movieName = "";
if (isset($_GET['movie'])) {
    $movieName = $_GET['movie'];
    $sqlMovies = "SELECT * FROM movies WHERE title = '$movieName'"; 
}

$result = mysqli_query($con, $sqlMovies);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $movieid = $row['movie_id'];
    $title = $row['title'];
    $description = $row['description'];
    $year = $row['year_release'];
    $rate = $row['rated'];
    $duration = $row['time'];
    $poster = $row['poster'];
} else {
    echo "Error: " . mysqli_error($con);
}

$sqlMovieShowTime = "SELECT showtime_id, showtime FROM showtimes WHERE movie_id = '$movieid' LIMIT 10";
$resultShowTime = mysqli_query($con, $sqlMovieShowTime);

$sqlCast = "SELECT * FROM actors WHERE movie_id = '$movieid'"; 
$resultCast = mysqli_query($con, $sqlCast);
$cast = [];
if ($resultCast) {
    while ($row = mysqli_fetch_assoc($resultCast)) {
        $cast[] = $row['actors'];
    }
} else {
    echo "Error: " . mysqli_error($con);
}

$sqlGenre = "SELECT * FROM genre WHERE movie_id = '$movieid'";
$resultGenre = mysqli_query($con, $sqlGenre);
$genre = [];
if ($resultGenre) {
    while ($row = mysqli_fetch_assoc($resultGenre)) {
        $genre[] = $row['genre'];
    }
} else {
    echo "Error: " . mysqli_error($con);
}

$showtimesID = [];
$showtimes = [];
$showtime_id = '';

if ($resultShowTime) {
    while ($row = mysqli_fetch_assoc($resultShowTime)) {
        $showtimesID[] = $row['showtime_id'];
        $showtimes[] = $row['showtime'];
    }
} else {
    echo "Error: " . mysqli_error($con);
}

// Store the current URL (movie.php) in session variable
$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

function RemoveSpecialChar($str) {
    $res = str_replace('&', 'and', $str);
    $res = preg_replace('/[^\w\s\-]/', '', $res);
    $res = preg_replace('/\s+/', ' ', $res);
    $res = trim($res);
    $res = strtolower($res);
    return $res;
}
?>

<html>
    <style>
        body {
            margin: 0;
            background: black;
        }
        video {
            position: fixed; 
            top: 50%;
            left: 50%; 
            transform: translate(-50%, -50%) scale(1);
            width: auto; 
            min-width: 100%; 
            min-height: 100%; 
            filter: grayscale(60%);
            z-index: 0;
        }
        h1 {
            text-align: left;
            font-family: title;
            font-size: 100px;
            font-weight: 100;
            color: white;
            margin: 0;
            opacity: 0;
            animation: 500ms moveleft 0s cubic-bezier(.73,0,.27,1) forwards;
        }
        h2 {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: darkseagreen;
            font-weight: 600;
            font-size: 15px;
            opacity: 0;
            animation: 500ms moveleft .05s cubic-bezier(.73,0,.27,1) forwards;
        }
        .gc {
            font-family: spartan;
            font-weight: 600;
            font-size: 20px;
            margin-top: 5px;
            color: white;
            display: flex;
            opacity: 0;
            animation: 500ms moveleft .15s cubic-bezier(.73,0,.27,1) forwards;
        }
        h4 {
            margin-right: 30px;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 15px;
        }
        p {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: darkgray;
            font-weight: 500;
            font-size: 15px;
            width: 60%;
            opacity: 0;
            animation: 500ms moveleft .1s cubic-bezier(.73,0,.27,1) forwards;
        }
        .gradient {
            z-index: 100;
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgb(0,0,0);
            background: linear-gradient(90deg, rgba(0,0,0,0.9192051820728291) 30%, rgba(0,0,0,0.4066001400560224) 80%, rgba(0,212,255,0) 100%);
         }
        .content {
            position: absolute;
            bottom: 10%;
            margin: 20 200;
            animation: 500ms moveleft2 0s cubic-bezier(.73,0,.27,1) forwards;
        }
        @font-face {
            font-family: title;
            src: url(font/TACKERLEN.otf);
        }
        @font-face {
            font-family: description;
            src: url(font/DELAQRUS.otf);
        }
        @font-face {
            font-family: spartan;
            src: url(font/LeagueSpartan-VariableFont_wght.ttf);
        }
        #ticket {
            padding: 10px;
            margin-top: 15px;
            border-radius: 0;
            font-size: 30px;
            font-family: title;
            width: 300px;
            opacity: 0;
            animation: 500ms moveleft .2s cubic-bezier(.73,0,.27,1) forwards;
            cursor: pointer;
            border: 2px solid white;
            background: #36454F;
            color: white;
            transition-duration: 200ms;
        }
        #ticket:hover {
            background: white;
            color: gray;
        }
        .tabs {
            width: 10px;
            height: 100%;
            background: SlateBlue;
            position: relative;
            z-index: 100;
            transition-duration: 100ms;
            transition-timing-function: ease-in-out;
        }
        .tabs:hover {
            width: 150px;
            opacity: .5;
        }
        @keyframes moveleft {
            from {
                margin-left: 50px;
                opacity: 0;
            }
            to {
                margin-left: 0px;
                opacity: 1;
            }
        }
        @keyframes moveleft2 {
            from {
                margin-left: 150px;
                opacity: 0;
            }
            to {
                margin-left: 100px;
                opacity: 1;
            }
        }
        @keyframes moveleft3 {
            from {
                margin-left: 50px;
                opacity: 0;
            }
            to {
                margin-left: 100px;
                opacity: 1;
            }
        }
        .back {
            line-height: 0;
            animation: 500ms moveleft3 .1s cubic-bezier(.73,0,.27,1) forwards;
            opacity: 0;
            transform: scaleX(-1);
            width: 20px;
            height: 15px;
            margin: 100px;
            padding: 10px;
            text-align: center;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            border: 2px solid white;
            border-radius: 100px;
            transition-duration: 200ms;
            position: fixed;
            left: 0;
            top: 0;
        }
        .back:hover {
            background: #36454F;
            cursor: pointer;
        }
    </style>
    <body>
        <form action="ticket.php" method="get">
            <input type="hidden" value="<?php echo $poster?>" name="poster">
            <input type="hidden" value="<?php echo $acc_type?>" name="acc_type">
            <input type="hidden" value="<?php echo $acc_key?>" name="acc_key">
            <input type="hidden" value="<?php echo $acc_id?>" name="acc_id">
            <input type="hidden" value="<?php echo $showtime_id ?>" name="showtime_id">
            <input type="hidden" value="<?php echo $movieName ?>" name="movie">
            <input type="hidden" value="<?php echo $movieid ?>" name="movieid">
            <input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="return_url">
            <?php foreach ($showtimes as $time) {
                echo "<input type='hidden' name='showtimes[]' value='$time'>";
            } ?>
            <?php foreach ($showtimesID as $time) {
                echo "<input type='hidden' name='showtimesID[]' value='$time'>";
            } ?>
            <div class="gradient">
                <div class="back" onclick="window.location = 'index.php'">➤</div>
                <div class="content">
                    <h1><?php echo $title; ?></h1>
                    <h2><?php echo $year . " ● " . $rate . " ● " . $duration; ?></h2>
                    <p><?php echo $description; ?></p>
                    <div class="gc">
                        <h4>
                            Cast: <?php echo " | "; foreach ($cast as $result) {
                                echo $result . " | ";
                            } ?>
                        </h4>
                        <h4>
                            Genre: <?php echo " | "; foreach ($genre as $result) {
                                echo $result . " | ";
                            } ?>
                        </h4>
                    </div>
                    <input id="ticket" type="submit" value="Buy Tickets!">
                </div>
            </div>
            <video id="trailer" autoplay loop plays-inline width="100%" type="video/mp4" src="trailer/<?php echo RemoveSpecialChar($movieName)?>.mp4"></video>
        </form>
    </body>
</html>

<?php mysqli_close($con); ?>
