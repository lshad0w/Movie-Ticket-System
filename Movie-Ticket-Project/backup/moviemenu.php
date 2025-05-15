<?php
session_start(); // Start or resume session

// Include database connection
include('dbconnect.php');

// Check database connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve data from POST or GET as needed
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
// Validate session variables with POST or GET parameters
if ($_SESSION['acc_id'] !== $acc_id || $_SESSION['acc_key'] !== $acc_key || $_SESSION['acc_type'] !== $acc_type) {
    echo "Error: Session information does not match.";
    exit();
}

// Query to count total movies
$cntmovie_qry = "SELECT COUNT(*) as total FROM movies";
$cntmovie_rslt = mysqli_query($con, $cntmovie_qry);
$total = 0;
if(mysqli_num_rows($cntmovie_rslt) > 0){
    while($row = mysqli_fetch_assoc($cntmovie_rslt)){
        $total = $row['total'] + 1;
        $counter = str_pad($total, 3, '0', STR_PAD_LEFT);
        $movie_id_db = "Movie ID_".$counter;
    }
}

// Delete movie data from table if movie_id is present in GET
if (isset($_GET['movie_id'])) {
    $movies_id = $_GET['movie_id'];
    $delete = mysqli_query($con, "DELETE FROM `movies` WHERE `movie_id` = '$movies_id'");
}

// Retrieve all movies from database
$select = "SELECT * FROM movies";
$query = mysqli_query($con, $select);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movie Editor</title>
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
        td img {
            max-width: 150px;
        }
        .description-column {
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .container {
            margin-left: 50px;
        }
        .createbtn {
            width: 200px;
            padding: 10px;
            margin-top: 20px;
        }
        button {
            width: 100%;
            padding: 20px;
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
        <div class="containertop"><h1>Movie Editor</h1></div>
        <div class="container">
            <table>
                <tr>
                    <th style="width: 200px;">Title</th>
                    <th style="width: 120px;">Release Date</th>
                    <th style="width: 80px;">Rated</th>
                    <th style="width: 80px;">Time</th>
                    <th class="description-column">Description</th>
                    <th style="width: 150px;">Poster</th>
                    <th colspan="2" style="width: 120px;">Operations</th>
                </tr>
                <?php
                $num = mysqli_num_rows($query);
                if ($num > 0){
                    while($result = mysqli_fetch_array($query)){
                ?>
                        <tr>
                            <td><?php echo $result['title'] ?></td>
                            <td><?php echo $result['year_release'] ?></td>
                            <td><?php echo $result['rated'] ?></td>
                            <td><?php echo $result['time'] ?></td>
                            <td class="description-column"><?php echo $result['description'] ?></td>
                            <td><img src="<?php echo "poster/" . $result['poster']; ?>" width="150px"></td>
                            <td><a href='updatemovie.php?movie_id=<?php echo $result['movie_id'] ?>' class='edtbtn'>Edit</a></td>
                            <td><a href='moviemenu.php?movie_id=<?php echo $result['movie_id'] ?>' class='delbtn'>Delete</a></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </table>
        </div>
        <div class="container">
            <input class="createbtn" type="button" value="Insert New Movie" name="insertMovie" id="insertMovie" onclick="window.location='insertmovie.php'">
        </div>
        <form class="" action="moviemenu.php" method="post" autocomplete="off" enctype="multipart/form-data">  
            <div class="container">
                <label><b>Upload Images here first to be able to use for data base editing or inserting a new movie</b></label>
                <input type="file" value="Upload image" name="newImage" id="newImage">                         
                <input class="createbtn" type="submit" value="Upload" name="Submit1">
            </div>
        </form>
    </div>
</body>
</html>
