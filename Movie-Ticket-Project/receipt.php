<?php
session_start();
include('dbconnect.php');

// Check if session variables are set for security
if (!isset($_SESSION['acc_id']) || !isset($_SESSION['acc_key']) || !isset($_SESSION['acc_type'])) {
    header('Location: login.php');
    exit();
}

// Fetch session variables
$seats = $_SESSION['seats'];
$totalPrice = $_SESSION['totalPrice'];
$movieId = $_SESSION['movieid'];
$movieName = $_SESSION['movie'];
$showtime_id = $_SESSION['showtime_id'];
$acc_id = $_SESSION['acc_id'];
$acc_key = $_SESSION['acc_key'];
$acc_type = $_SESSION['acc_type'];
$newBalance = $_SESSION['newBalance'];

$required_session_vars = ['seats', 'totalPrice', 'movieid', 'movie', 'showtime_id', 'acc_id', 'acc_key', 'acc_type', 'newBalance'];

foreach ($required_session_vars as $var) {
    if (!isset($_SESSION[$var])) {
        // Redirect to index.php if any required session variable is not set
        header('Location: index.php');
        exit();
    }
}

// Insert reservation into database
foreach ($seats as $chosenSeat) {
    $sqlReserved = "INSERT INTO reservation (seat_number, user_id, showtime_id, status)
                    VALUES ('$chosenSeat', '$acc_id', '$showtime_id', 'reserved')";
    if (!mysqli_query($con, $sqlReserved)) {
        echo "Error: " . $sqlReserved . "<br>" . mysqli_error($con);
        mysqli_close($con);
        exit;
    }
}

// Output success message and details
$receipt = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            background: url(poster/background.jpg);
            margin: 0; /* Reset default margin */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Ensure full viewport height */
            overflow: hidden;
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
        
        @font-face {
            font-family: spartan;
            src: url(font/LeagueSpartan-VariableFont_wght.ttf);
        }
        
        .container {
            text-align: center; /* Center align everything inside */
        }
        
        .succes {
            font-family: spartan;
            font-weight: 900;
            color: white;
            font-size: 100px;
            line-height: 1.3; /* Adjust line height for better spacing */
        }
        
        .thankyou {
            font-family: spartan;
            border: 0;
            color: white;
            font-size: 50px;
            font-weight: 700;
            background-color: #A7C7E7;
            padding: 20px 70px 10px 70px; /* Adjust padding as needed */
            display: inline-block; /* Make it inline-block to center using text-align */
            -webkit-animation: glow 1s ease-in-out infinite alternate;
            -moz-animation: glow 1s ease-in-out infinite alternate;
            animation: glow 3s ease-in-out infinite alternate;
            box-shadow: 0 0 5px #A7C7E7, 0 0 25px #A7C7E7, 0 0 50px #A7C7E7, 0 0 200px #A7C7E7;

        }
        .content {
            -webkit-box-reflect: below 0px linear-gradient(to bottom, rgba(0,0,0,0.0), rgba(0,0,0,0.05));        
        }
        .fireworks {
            z-index: 999;
            position: absolute;
            width: 1600px;
            height: 900px;
        }
        .burst {
            position: absolute;
        }
        .burst > .line {
            width: 10px;
            height: 10px;
            position: absolute;
            background-color: white;
        }
        .burst > .line:nth-child(1) {
    width: 10px;
    height: 10px;
    position: absolute;
    background-color: #f44e37;
    animation: move1 1s linear infinite;
    opacity: 0;
    box-shadow: 0 0 0px #f44e37, 0 0 5px #f44e37, 0 0 25px #f44e37, 0 0 50px #f44e37;
}
@keyframes move1 {
    0% {
        transform: translate(0, 0);
    }
    50% {
        transform: translate(0, -90px);
        opacity: 1;
    }
    100% {
        transform: translate(0, -140px);
        opacity: 0;
    }
}

.burst > .line:nth-child(2) {
    width: 10px;
    height: 10px;
    position: absolute;
    background-color: #e34775;
    animation: move2 1s linear infinite;
    opacity: 0;
    box-shadow: 0 0 0px #e34775, 0 0 5px #e34775, 0 0 25px #e34775, 0 0 50px #e34775;
}
@keyframes move2 {
    0% {
        transform: rotate(45deg) translate(0, 0);
    }
    50% {
        transform: rotate(45deg) translate(0, -90px);
        opacity: 1;
    }
    100% {
        transform: rotate(45deg) translate(0, -140px);
        opacity: 0;
    }
}

.burst > .line:nth-child(3) {
    width: 10px;
    height: 10px;
    position: absolute;
    background-color: #d649ae;
    animation: move3 1s linear infinite;
    opacity: 0;
    box-shadow: 0 0 0px #d649ae, 0 0 5px #d649ae, 0 0 25px #d649ae, 0 0 50px #d649ae;
}
@keyframes move3 {
    0% {
        transform: rotate(90deg) translate(0, 0);
    }
    50% {
        transform: rotate(90deg) translate(0, -90px);
        opacity: 1;
    }
    100% {
        transform: rotate(90deg) translate(0, -140px);
        opacity: 0;
    }
}

.burst > .line:nth-child(4) {
    width: 10px;
    height: 10px;
    position: absolute;
    background-color: #ca4de4;
    animation: move4 1s linear infinite;
    opacity: 0;
    box-shadow: 0 0 0px #ca4de4, 0 0 5px #ca4de4, 0 0 25px #ca4de4, 0 0 50px #ca4de4;
}
@keyframes move4 {
    0% {
        transform: rotate(135deg) translate(0, 0);
    }
    50% {
        transform: rotate(135deg) translate(0, -90px);
        opacity: 1;
    }
    100% {
        transform: rotate(135deg) translate(0, -140px);
        opacity: 0;
    }
}

.burst > .line:nth-child(5) {
    width: 10px;
    height: 10px;
    position: absolute;
    background-color: #b0a7ae;
    animation: move5 1s linear infinite;
    opacity: 0;
    box-shadow: 0 0 0px #b0a7ae, 0 0 5px #b0a7ae, 0 0 25px #b0a7ae, 0 0 50px #b0a7ae;
}
@keyframes move5 {
    0% {
        transform: rotate(180deg) translate(0, 0);
    }
    50% {
        transform: rotate(180deg) translate(0, -90px);
        opacity: 1;
    }
    100% {
        transform: rotate(180deg) translate(0, -140px);
        opacity: 0;
    }
}

.burst > .line:nth-child(6) {
    width: 10px;
    height: 10px;
    position: absolute;
    background-color: #ca4de4;
    animation: move6 1s linear infinite;
    opacity: 0;
    box-shadow: 0 0 0px #ca4de4, 0 0 5px #ca4de4, 0 0 25px #ca4de4, 0 0 50px #ca4de4;
}
@keyframes move6 {
    0% {
        transform: rotate(225deg) translate(0, 0);
    }
    50% {
        transform: rotate(225deg) translate(0, -90px);
        opacity: 1;
    }
    100% {
        transform: rotate(225deg) translate(0, -140px);
        opacity: 0;
    }
}

.burst > .line:nth-child(7) {
    width: 10px;
    height: 10px;
    position: absolute;
    background-color: #d649ae;
    animation: move7 1s linear infinite;
    opacity: 0;
    box-shadow: 0 0 0px #d649ae, 0 0 5px #d649ae, 0 0 25px #d649ae, 0 0 50px #d649ae;
}
@keyframes move7 {
    0% {
        transform: rotate(270deg) translate(0, 0);
    }
    50% {
        transform: rotate(270deg) translate(0, -90px);
        opacity: 1;
    }
    100% {
        transform: rotate(270deg) translate(0, -140px);
        opacity: 0;
    }
}

.burst > .line:nth-child(8) {
    width: 10px;
    height: 10px;
    position: absolute;
    background-color: #e34775;
    animation: move8 1s linear infinite;
    opacity: 0;
    box-shadow: 0 0 0px #e34775, 0 0 5px #e34775, 0 0 25px #e34775, 0 0 50px #e34775;
}
@keyframes move8 {
    0% {
        transform: rotate(315deg) translate(0, 0);
    }
    50% {
        transform: rotate(315deg) translate(0, -90px);
        opacity: 1;
    }
    100% {
        transform: rotate(315deg) translate(0, -140px);
        opacity: 0;
    }
}
.thankyou {
    z-index: 0010; /* Ensure the button is above the fireworks */
    transition-duration: 300ms;
}
.thankyou:hover {
    cursor: pointer;
    background-color: #5e9fff;
    box-shadow: 0 0 5px #5e9fff, 0 0 25px #5e9fff, 0 0 50px #5e9fff, 0 0 200px #5e9fff;

}
.fireworks {
    z-index: -1; /* Lower z-index for the fireworks */
    position: absolute;
    width: 1600px;
    height: 900px;
}
    </style>
</head>
<body>
<div class="fireworks">
        <div class="burst">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <div class="succ">
                <span class="succes">PURCHASE SUCCESS</span>
            </div>
            <div class="ty">
                <button class="thankyou" onclick="returnHome()" type="button">THANK YOU!!</button>
            </div>
        </div>
    </div>

    
<div class="background"></div>
</body>
<script>
    function createBurst() {
        let fireworks = document.querySelector(".fireworks");
        let burst = document.querySelector(".burst");
        burst.style.top = Math.random() * 900 + "px";
        burst.style.left = Math.random() * 1600 + "px";

        let burstClone = burst.cloneNode(true);
        fireworks.appendChild(burstClone);

        setTimeout(() => {
            burstClone.remove();
        }, 2000)
    }
    setInterval(createBurst, 1000)

    function returnHome() {
        window.location.href = "clear_session.php";
    }
</script>
</html>
';

echo $receipt;

mysqli_close($con);
?>
