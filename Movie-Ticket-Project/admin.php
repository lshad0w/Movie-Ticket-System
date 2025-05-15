<?php 
    include('dbconnect.php');
?>
<?php
function myFunction() {
    echo "Button clicked!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Button with PHP Function</title>
</head>
<body>
    <form method="get" action="admin.php">
        <button type="submit" name="submit" value="add">Add</button>
        <button type="submit" name="submit" value="delete">Delete</button>
    </form>

    <?php
    if(isset($_GET['submit'])) {

        $submitted = $_GET['submit'];

        myFunction();
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            if ($submitted == 'add') {
            $sqlInsert = "INSERT INTO movies (`title`, `year_release`, `rated`, `time`, `description`, `genre`, `cast`, `poster`)
            VALUES ('Testing Movie', '2025', 'SPG', '2h 50m', 'borat', 'comedy', 'Cj Rojo', 'one punch man.jpg')";
            } else if ($submitted == 'delete')  {
            $sqlInsert = "DELETE FROM movies WHERE title = 'Testing Movie'";
            }
        }
    
        if (mysqli_query($con, $sqlInsert)) {
            echo "Success";
        } else {
            echo "Error creating table: " . mysqli_error($con);
        }
    }
    mysqli_close($con);
    ?>
</body>
</html>
