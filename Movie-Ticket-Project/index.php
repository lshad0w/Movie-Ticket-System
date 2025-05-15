<?php 
session_start(); // Start session

// Include database connection
include('dbconnect.php');

/*
// Check if session variables are set
if (!isset($_SESSION['acc_id']) || !isset($_SESSION['acc_key']) || !isset($_SESSION['acc_type'])) {
    // Redirect to login page if session variables are not set
    header('Location: login.php');
    exit();
}

// Get session variables
$acc_id = $_SESSION['acc_id'];
$acc_key = $_SESSION['acc_key'];
$acc_type = $_SESSION['acc_type'];

// Handle admin-specific functionality
if ($acc_type == 'admin') {
    include('adminpopup.php');
}

// Function to sanitize and remove special characters from strings
function RemoveSpecialChar($str) {
    $res = str_replace('&', 'and', $str);
    $res = preg_replace('/[^\w\s\-]/', '', $res);
    $res = preg_replace('/\s+/', ' ', $res);
    $res = trim($res);
    $res = strtolower($res);
    return $res;
}
*/
// Default filter value
$filter = "All";

?>
<html>
    <head>
        <title>Home Page</title>
    </head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        img {
            transition-duration: 250ms;
            width: 144px;
            height: 216px;
        }
        img:hover{
            transform: rotate(3deg);
            filter: grayscale(.5);
            cursor: pointer;
        }
        .movie {
            margin: 2.5px 2.5px;
        }
        .movies {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
            height: 75vh;
            
        }
        .movieColDown, .movieColUp {
            display: inline-block;
            vertical-align: top;
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            opacity: 0;
            z-index: 1;
        }
        
        @keyframes moveDown {
            from {
                margin-top: 50px;
                opacity: 0;
            }
            to {
                margin-top: 0px;
                opacity: 1;
            }
        }
        @keyframes moveUp {
            from {
                opacity: 0;
                margin-bottom: 50px;
            }
            to {
                margin-top: 0px;
                opacity: 1;
            }
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
        .background {
            position: fixed;
            left: 0;
            top: 0;
            background-image: url(poster/background.jpg);
            background-position: center;
            background-size: cover;
            filter: brightness(30%);
            width: 100%;
            height: 100%;
            z-index: -100;
        }
        [type=radio]:checked + img {
                outline: 2px solid #f00;
        }
        [type=radio] { 
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        [type=radio] + img {
            cursor: pointer;
        }
        [type=radio]:disabled + img {
            opacity: 0.1;
        }
        .filter {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            z-index: 100;
        }
        [type=submit] {
            width: 100px;
            height: 35px;
            margin-top: 10%;
            background: none;
            color: white;
            transition-duration: 200ms;
            border: 2px solid #36454F;
            font-family: spartan;
            cursor: pointer;
            animation: 500ms buttonUp 0s cubic-bezier(.73,0,.27,1) forwards;
        }
        @keyframes buttonUp {
            from {
                
                opacity: 0;
            }
            to {
                opacity: 1;
            
            }
        }
        [type=submit]:hover {
            background: #36454F;
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
    

    .logout-container {
    position: fixed;
    top: 10px;
    right: 10px;
  }
  .logout-button {
    padding: 10px 20px;
    background-color: black;
    color: white;
    border: solid 2px grey;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;

    &:hover {
    background-color: #d32f2f; 
  }
}
    </style>
    <body>  
            <div class="logout-container">
                <button class="logout-button" onclick="window.location='logout.php'">Log-out</button>
            </div>
            <div id="out" class="modal">

        <div class="filter">
        <form action="index.php" id="filter" method="get">
            
            <input type="submit" value="Action" name="filter" onclick="submitFilter()">
            <input type="submit" value="Horror" name="filter" onclick="submitFilter()">
            <input type="submit" value="Comedy" name="filter" onclick="submitFilter()">
            <input type="submit" value="Drama" name="filter" onclick="submitFilter()">
            <input type="submit" value="Romance" name="filter" onclick="submitFilter()">
            <input type="submit" value="Adventure" name="filter" onclick="submitFilter()">
            <input type="submit" value="Thriller" name="filter" onclick="submitFilter()">
            <input type="submit" value="Animation" name="filter" onclick="submitFilter()">
            <input type="submit" value="Crime" name="filter" onclick="submitFilter()">
            <button type="submit" name="filter" onclick="submitFilter()" value="">All</button>
        </form></div>
        <?php 
    
            $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        ?>
        <form action="movie.php" id="formID">
        <audio src="audio/selected.mp3" id="selected"></audio>
        <div class="movies">
            <?php 
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                } else {
                    $sqlGenre = "SELECT movie_id, genre FROM genre";
                    $resultGenre = mysqli_query($con, $sqlGenre);
                    
                    $genreArray = [];
                    while ($row = mysqli_fetch_assoc($resultGenre)) {
                        $genreArray[$row['movie_id']][] = $row['genre'];
                    }
                    
                    $sqlMovies = "SELECT poster, title, movie_id FROM movies"; 
                    $result = mysqli_query($con, $sqlMovies);
                    if ($result) {
                        $change = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Directly assign $poster within the loop
                            $poster = $row['poster'];
                            $title = $row['title'];
                            $id = $row['movie_id'];
                            $colClass = ($change % 2 == 0) ? 'movieColUp" style="animation: 500ms moveUp '.($change*0.01).'s cubic-bezier(.73,0,.27,1) forwards;' : 'movieColDown" style="animation: 500ms moveDown '.(0.01*$change).'s cubic-bezier(.73,0,.27,1) forwards;';
                            $disabled = ($filter != '' && !in_array($filter, $genreArray[$id])) ? 'disabled' : '';
                        
                            if ($change % 3 == 0) {
                                echo '<div class="' . $colClass . '">';
                            }
                            echo '
                            <div class="movie">
                                <label>
                                    <input type="radio" name="movie" value="'.$title.'" ' . $disabled . '>
                                    <img src="poster/'. $poster .'" id="movie' . $id . '">
                                </label>
                            </div>
                            ';
                            if (($change + 1) % 3 == 0) {
                                echo '</div>'; 
                            }
                            $change++;
                        }
                        if ($change % 3 != 0) {
                            echo '</div>';
                        }
                    } else {
                        echo "Error: " . mysqli_error($con);
                    }
                    mysqli_close($con);
                }
            ?>
        </div>
    </form> 
           
        <div class="background"></div>     
         
    </body>
    <script>
        var radios = document.querySelectorAll('input[type=radio]');

        window.addEventListener('unload', function(event) {
            document.getElementById("formID").reset();
        }, false);

        radios.forEach(function(radio) {
            radio.addEventListener('change', showInfo);
            radio.checked = false;
        });

        var movies = document.querySelectorAll('img');

        movies.forEach(function(movie) {
            movie.addEventListener('mouseover', playSound);
        });

        function playSound() {
            var selected = document.getElementById("selected");
            selected.currentTime = 0;
            selected.play();
        }

        function showInfo() {
            document.getElementById("formID").submit();
        }

        function submitFilter() {
            document.getElementById("filter").submit();
        }
    </script>

</html>
