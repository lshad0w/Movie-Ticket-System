<?php
session_start(); // Start or resume session

$seats = isset($_GET['seats']) ? $_GET['seats'] : [];
$movieid = isset($_GET['movieid']) ? $_GET['movieid'] : '';
$poster = isset($_GET['poster']) ? $_GET['poster'] : '';
$moviename = isset($_GET['movie']) ? $_GET['movie'] : '';
$showtime_id = isset($_GET['showtime_id']) ? $_GET['showtime_id'] : '';
$seatsNumber = isset($_GET['seatsNumber']) ? intval($_GET['seatsNumber']) : 0;
$totalPrice = $seatsNumber * 250;
$acc_type = isset($_GET['acc_type']) ? $_GET['acc_type'] : '';
$acc_key = isset($_GET['acc_key']) ? $_GET['acc_key'] : '';
$acc_id = isset($_GET['acc_id']) ? $_GET['acc_id'] : '';

if (!isset($_SESSION['acc_id']) || !isset($_SESSION['acc_key']) || !isset($_SESSION['acc_type'])) {
    header('Location: login.php');
    exit();
}

function RemoveSpecialChar($str) {
    $res = str_replace('&', 'and', $str);
    $res = preg_replace('/[^\w\s\-]/', '', $res);
    $res = preg_replace('/\s+/', ' ', $res);
    $res = trim($res);
    $res = strtolower($res);
    return $res;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }
        @font-face {
            font-family: description;
            src: url(font/DELAQRUS.otf);
        }
        @font-face {
            font-family: spartan;
            src: url(font/LeagueSpartan-VariableFont_wght.ttf);
        }
        body {
            font-family: spartan;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            z-index: 10;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 45%;
        }
        h2 {
            color: #333;
        }
        label {
            display: block;
            color: #666;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        input[type="password"] {
            width: 0;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        input[type="text"] {
            animation: 1s widthOnly 0s cubic-bezier(.73,0,.27,1) forwards;
        }
        input[type="email"] {
            animation: 1s widthOnly .05s cubic-bezier(.73,0,.27,1) forwards;
        }
        input[type="number"] {
            animation: 1s widthOnly .1s cubic-bezier(.73,0,.27,1) forwards;
        }
        input[type="date"] {
            animation: 1s widthOnly .15s cubic-bezier(.73,0,.27,1) forwards;

        }
        input[type="password"] {
            animation: 1s widthOnly .2s cubic-bezier(.73,0,.27,1) forwards;
        }
        .submit-btn {
            background-color: #28a745;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            animation: 1s fadeOnly 0s cubic-bezier(.73,0,.27,1) forwards;
        }
        .submit-btn:hover {
            background-color: #218838;
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
        .container > * {
            margin: 10px;
        }
        label {
            margin-top: 0;
            margin-bottom: 0;
        }
        .form-group {
            animation: 1s fadeOnly 0s cubic-bezier(.73,0,.27,1) forwards;
        }
        img {
        z-index: 0;
        transform: 
        perspective(639px)
        rotateX(0deg)
        rotateY(20deg)
        rotateZ(0deg);
        -webkit-box-reflect: below -0px linear-gradient(to bottom, rgba(0,0,0,0.0), rgba(0,0,0,0.1));
        border: 5px solid white;
        animation: 1s flipimage 0s cubic-bezier(.73,0,.27,1) forwards;
        opacity: 0;
        
        }
        @keyframes flipimage {
            from {
                transform: 
                perspective(639px)
                rotateX(0deg)
                rotateY(300deg)
                rotateZ(0deg);
                opacity: 0;
            }
            to {
                transform: 
                perspective(639px)
                rotateX(0deg)
                rotateY(380deg)
                rotateZ(0deg);
                opacity: 1;
            }
        }
        @keyframes fadeOnly {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes widthOnly {
            from {
                width: 0;
            }
            to {
                width: 300px;
            }
        }
        .back {
            line-height: 0;
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
            transform-origin: center;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="back" onclick="window.history.back()">➤</div>
    <div class="container">
        <img src="poster/<?php echo $poster?>" height="300px">
        <form action="verify_payment.php" method="get">
            <?php foreach ($seats as $seat) : ?>
                <input type="hidden" name="seats[]" value="<?php echo htmlspecialchars($seat); ?>">
            <?php endforeach; ?>
            <input type="hidden" value="<?php echo $acc_type; ?>" name="acc_type">
            <input type="hidden" value="<?php echo $acc_key; ?>" name="acc_key">
            <input type="hidden" value="<?php echo $acc_id; ?>" name="acc_id">
            <input type="hidden" value="<?php echo $actual_link; ?>" name="actual_link">
            <input type="hidden" value="<?php echo htmlspecialchars($movieid); ?>" name="movieid">
            <input type="hidden" value="<?php echo htmlspecialchars($moviename); ?>" name="movie">
            <input type="hidden" value="<?php echo htmlspecialchars($showtime_id); ?>" name="showtime_id">
            <input type="hidden" value="<?php echo htmlspecialchars($totalPrice); ?>" name="totalPrice">
            <div class="form-group">
                <label for="name">Name on Card</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="number" id="card_number" name="card_number" required>
            </div>
            <div class="form-group">
                <label for="exp_date">Expiration Date</label>
                <input type="date" id="exp_date" name="exp_date" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="password" id="cvv" name="cvv" required>
            </div>
            <button type="submit" class="submit-btn">Submit Payment</button>
        </form>
    </div>
    </div>
</body>
</html>
