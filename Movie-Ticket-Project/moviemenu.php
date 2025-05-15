<?php
session_start(); // Start or resume session

// Include database connection
include('database.php');

/* ito preeee
 Check database connection
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
*/

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



//to delete user data on table
if (isset($_GET['movie_id'])) {
    $movies_id=$_GET['movie_id']; 
     // Optionally, delete associated actors and genres
     $delete_actors = "DELETE FROM actors WHERE movie_id='$movies_id'";
     $con->query($delete_actors);

     $delete_genre = "DELETE FROM genre WHERE movie_id='$movies_id'";
     $con->query($delete_genre);

    $delete=mysqli_query($con,"DELETE FROM `movies` WHERE `movie_id` ='$movies_id'"); 
    

}

/*displaying data on table */
$select="SELECT movies.*, GROUP_CONCAT(DISTINCT actors.actors) AS actors, GROUP_CONCAT(DISTINCT genre.genre) AS genres
FROM movies
LEFT JOIN actors ON movies.movie_id = actors.movie_id
LEFT JOIN genre ON movies.movie_id = genre.movie_id
GROUP BY movies.movie_id";
$query=mysqli_query($con,$select);


//Choose an image or video to be able to insert and display in database table
if(isset($_POST['Submit1'])) { 
    // Check if a file is uploaded
    if(isset($_FILES["newFile"]) && $_FILES["newFile"]["error"] == 0) {
        $fileType = strtolower(pathinfo($_FILES["newFile"]["name"], PATHINFO_EXTENSION));
        $allowedTypes = array("jpg", "jpeg", "png", "gif", "mp4", "avi", "mov", "wmv");
        if(in_array($fileType, $allowedTypes)) {
            $filepath = "image/" . $_FILES["newFile"]["name"]; // Adjust the directory where you want to store files bali yung image/ lang uli papalitan depends on wanted location of file
            if(move_uploaded_file($_FILES["newFile"]["tmp_name"], $filepath)) {
                if(in_array($fileType, array("jpg", "jpeg", "png", "gif"))) {
                    echo "<script>alert('Image Successfully Uploaded');</script>";
                } else {
                    echo "<script>alert('Video Successfully Uploaded');</script>";
                }
            } else {
                echo "<script>alert('Error Uploading File');</script>";
            }
        } else {
            echo "<script>alert('Invalid File Type');</script>";
        }
    }
}
/* ito pree
// Delete movie data from table if movie_id is present in GET
if (isset($_GET['movie_id'])) {
    $movies_id = $_GET['movie_id'];
    $delete = mysqli_query($con, "DELETE FROM `movies` WHERE `movie_id` = '$movies_id'");
}

// Retrieve all movies from database
$select = "SELECT * FROM movies";
$query = mysqli_query($con, $select);
*/
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
                    <th style="width: 150px;">Genre</th>
                    <th style="width: 150px;">Cast</th>
                    <th style="width: 150px;">Poster</th>
                    <th style="width: 150px;">Trailer</th>
                    <th colspan="2" style="width: 120px;">Operations</th>
                </tr>
                <?php
                $num = mysqli_num_rows($query);
                if ($num > 0){
                    while($result = mysqli_fetch_array($query)){
                ?>
                        <tr>
                                                <tr style="width:50%;">
                                                    <td><?php echo $result['title']?></td>                                            
                                                    <td><?php echo $result['year_release']?></td>
                                                    <td><?php echo $result['rated']?></td>
                                                    <td><?php echo $result['time']?></td>
                                                    <td ><?php echo $result['description']?></td>
                                                    <td><?php echo $result['actors']?></td>
                                                    <td ><?php echo $result['genres']?></td>
                                                                                        <td>
                                                    <?php  if (!empty($result['poster'])) { ?>
                                                    <img src="<?php echo "image/" .$result['poster']; ?>"> <!-- adjust mo lang uli yung image/ kung saan naka store yung media -->
                                                    <?php } else {
                                                    echo "No image added";
                                                    }   ?>                              </td>
                                                    <td> 
                                                    <?php  if (!empty($result['trailer'])) { ?>
                                                        <video width="320" height="240" controls>
                                                        <source src="<?php echo "image/" .$result['trailer'] ?>" type="video/mp4">  <!-- adjust mo lang uli yung image/ -->
                                                        </video>
                                                        <?php } else {
                                                        echo "No trailer added";
                                                    }   ?>
                                                    </td>   


                                                    <?php echo "
                                                    <td><a href='updatemovie.php?movie_id=".$result['movie_id']."' 
                                                    class='edtbtn'>Edit</a></td>
                                                    <td> <a href='moviemenu.php?movie_id=".$result['movie_id']."'  
                                                    class='delbtn'>Delete</a> </td>
                                                    "
                                                    ?>
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
                <label><b>Upload Images or Trailers here first to be able to use for data base editing or inserting a new movie</b></label>
                <input type="file" value="Upload image" name="newFile" id="newFile">                         
                <input class="createbtn" type="submit" value="Upload" name="Submit1">
            </div>
        </form>
    </div>
</body>
</html>
