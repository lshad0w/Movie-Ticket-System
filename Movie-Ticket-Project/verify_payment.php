
<html>
    <style>
        span {
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
            font-size: 25;
        }
        body {
            margin: 0;
            color: white;
            font-family: spartan;
        }
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('poster/background.jpg');
            background-position: center;
            background-size: cover;
            filter: brightness(30%);
            z-index: -1;
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
        button {
            margin: 10 0;
            padding: 5;
            border-radius: 0;
            border: 0;
            font-size: 15px;
            font-family: title;
            width: 130px;
            cursor: pointer;
            border: 2px solid white;
            background: #36454F;
            color: white;
        }
        button:hover {
            background: white;
            color: gray;
        }
    </style>
    <body>
        <div class="background"></div>
    </body>
</html>
<?php
session_start();
include('dbconnect.php');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$movieName = $_GET['movie'];
$showtime_id = $_GET['showtime_id'];
$seats = $_GET['seats'];
$movieId = $_GET['movieid'];
$totalPrice = $_GET['totalPrice'];
$acc_id = $_GET['acc_id'];
$acc_key = $_GET['acc_key'];
$acc_type = $_GET['acc_type'];
$name_on_card = $_GET['name'];
$email = $_GET['email'];
$card_number = $_GET['card_number'];
$exp_date = $_GET['exp_date'];
$cvv = $_GET['cvv'];

// Validate input (you should implement proper validation and sanitation)
$name_on_card = mysqli_real_escape_string($con, $name_on_card);
$email = mysqli_real_escape_string($con, $email);
$card_number = mysqli_real_escape_string($con, $card_number);
$exp_date = mysqli_real_escape_string($con, $exp_date);
$cvv = mysqli_real_escape_string($con, $cvv);

$sql = "SELECT * FROM payments WHERE name_on_card = '$name_on_card' AND email_address = '$email' AND card_number = '$card_number' AND expiration_date = '$exp_date' AND cvv = '$cvv'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentBalance = $row['balance'];

    if ($currentBalance >= $totalPrice) {
        $newBalance = $currentBalance - $totalPrice;

        // Start transaction
        $con->begin_transaction();

        $updateSql = "UPDATE payments SET balance = '$newBalance' WHERE name_on_card = '$name_on_card' AND email_address = '$email' AND card_number = '$card_number' AND expiration_date = '$exp_date' AND cvv = '$cvv'";
        $updateResult = $con->query($updateSql);

        if ($updateResult) {
            // Commit transaction
            $con->commit();

            // Store necessary session variables
            $_SESSION['seats'] = $seats;
            $_SESSION['totalPrice'] = $totalPrice;
            $_SESSION['movieid'] = $movieId;
            $_SESSION['movie'] = $movieName;
            $_SESSION['showtime_id'] = $showtime_id;
            $_SESSION['acc_id'] = $acc_id;
            $_SESSION['acc_key'] = $acc_key;
            $_SESSION['acc_type'] = $acc_type;
            $_SESSION['newBalance'] = $newBalance; 
            // Redirect to receipt.php
            header('Location: receipt.php');
            exit();
        } else {
            echo "<span>⚠ Failed to update balance.</span>";
        }
    } else {
        echo "<center><span>⚠ Insufficient Balance.<br><button onclick='window.history.back()'>Return</button></span></center>";
    }
} else {
    echo "<center><span>⚠ Card doesn't exist.<br><button onclick='window.history.back()'>Return</button></span></center>";
}

$con->close();
?>
