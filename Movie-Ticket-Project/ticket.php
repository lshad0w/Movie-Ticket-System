<?php
session_start(); // Start or resume session

include('dbconnect.php');

// Check if session variables are set, otherwise redirect to login page
if (!isset($_SESSION['acc_id']) || !isset($_SESSION['acc_key']) || !isset($_SESSION['acc_type'])) {
    header('Location: login.php');
    exit();
}
if (isset($_GET['return_url'])) {
    $return = $_GET['return_url'];
}
$showtime_id = $_GET['showtime_id'];
$acc_id = $_SESSION['acc_id'];
$acc_key = $_SESSION['acc_key'];
$acc_type = $_SESSION['acc_type'];

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    $seat = "SELECT * FROM reservation WHERE showtime_id = '$showtime_id'";
    $result = mysqli_query($con, $seat);
    $selectedSeats = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $selectedSeats[] = $row['seat_number'];
        }
    }
}

$movieName = $_GET['movie'];
$movieid = $_GET['movieid'];
$poster = $_GET['poster'];
$showtimes = isset($_GET['showtimes']) ? $_GET['showtimes'] : [];
$showtimesID = isset($_GET['showtimesID']) ? $_GET['showtimesID'] : [];

$numSeats = 200;

function generateSeatCheckboxes($numSeats, $selectedSeats) {
    $checkboxes = '<div class="seats-container">';
    for ($i = 1; $i <= $numSeats; $i++) {
        $groupDelay = ($i * 0.004);
        $disabled = in_array($i, $selectedSeats) ? 'disabled' : '';
        $checkboxes .= '<input type="checkbox" class="custom-checkbox" name="seats[]" value="' . $i . '" title="Seat No.' . $i . '" style="animation: 500ms seatsFade ' . $groupDelay . 's cubic-bezier(.73,0,.27,1) forwards;" ' . $disabled . '>';
        if ($i == 3 || $i == 23 || $i == 43 || $i == 63 || $i == 83 || $i == 103 || $i == 123 || $i == 143 || $i == 163 || $i == 183 || $i == 17 || $i == 37 || $i == 57 || $i == 77 || $i == 97 || $i == 117 || $i == 137 || $i == 157 || $i == 177 || $i == 197) {
            $checkboxes .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        if ($i % 20 == 0) {
            $checkboxes .= '</div><div class="seats-container">';
        }
    }
    $checkboxes .= '</div>';
    return $checkboxes;
}

?>
<html>
<style>
    [type=checkbox].custom-checkbox:disabled {
        background-color: #FF6961;
        border-color: #FF6961;
    }

    [type=checkbox].custom-checkbox:disabled::after {
        background-color: #FF6961;
    }

    body {
        margin: 0;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: white;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        width: 80%;
        height: 90vh;
        overflow: hidden;
        z-index: 1;
    }

    .container img {
        margin-bottom: 20px;
    }

    [type=radio]:checked+.time {
        background: white;
        color: black;
    }

    [type=radio] {
        opacity: 0;
        width: 0;
        height: 0;
    }

    [type=radio]+.time {
        cursor: pointer;
    }

    [type=checkbox].custom-checkbox {
        background: darkgrey;
        appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid darkgray;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        outline: none;
        opacity: 0;
    }

    .custom-checkbox {
        transition-duration: 200ms;
        cursor: pointer;
    }

    .custom-checkbox:hover {
        background: #777777;
    }

    @keyframes seatsFade {
        from {
            opacity: 0;
            margin-left: 7px;
        }

        to {
            opacity: 1;
            margin-left: 5px;
        }
    }

    [type=checkbox].custom-checkbox::after {
        content: '';
        background: darkgray;
        position: absolute;
        margin-top: 20px;
        margin-left: 0;
        width: 16px;
        height: 5px;
        color: white;
    }

    .showtimes-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    [type=checkbox].custom-checkbox:checked {
        background-color: #90EE90;
        border-color: #90EE90;
    }

    #amount {
        color: #90EE90;
    }

    [type=checkbox].custom-checkbox:checked::after {
        background: #90EE90;
    }

    .time:hover {
        background: #36454F;
    }

    .time {
        border: 2px solid #36454F;
        margin: 10px;
        font-family: spartan;
        padding: 15px;
        transition-duration: 200ms;
        opacity: 0;
        animation: 500ms buttonUp 0s cubic-bezier(.73,0,.27,1) forwards;
    }

    .seats-container {
        display: flex;
        justify-content: center;
        width: 100%;
        margin: 10px 0;
    }

    .seatsAmount {
        display: flex;
        justify-content: center;
        width: 100%;
        padding: 20 0;
        font-family: title;
    }

    .seatsAmount>span {
        margin: 0 5;
        font-size: 20px;
        opacity: 0;
        animation: 1s fadeOnly .2s cubic-bezier(.73,0,.27,1) forwards;
    }

    .background {
        position: fixed;
        background-image: url(poster/background.jpg);
        background-position: center;
        background-size: cover;
        filter: brightness(30%);
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    h1 {
        font-family: title;
        font-size: 70px;
        margin: 50 0;
        margin-top: 0;
        opacity: 0;
        animation: 1s titleUp .1s cubic-bezier(.73,0,.27,1) forwards;
        -webkit-box-reflect: below -35px linear-gradient(to bottom, rgba(0,0,0,0.0), rgba(0,0,0,0.1));
    }

    .seats {
        font-family: description;
    }

    @font-face {
        font-family: description;
        src: url(font/DELAQRUS.otf);
    }

    @font-face {
        font-family: spartan;
        src: url(font/LeagueSpartan-VariableFont_wght.ttf);
    }

    @keyframes buttonUp {
        from {
            margin: 20px;
            opacity: 0;
        }

        to {
            opacity: 1;
            margin: 5px;
        }
    }

    @keyframes titleUp {
        from {
            margin-top: 50px;
            opacity: 0;
        }

        to {
            margin-top: 0;
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

    @font-face {
        font-family: title;
        src: url(font/TACKERLEN.otf);
    }

    #submit {
        margin: 20 0;
        padding: 10;
        border-radius: 0;
        border: 0;
        font-size: 30px;
        font-family: title;
        width: 300px;
        cursor: pointer;
        border: 2        px solid white;
        background: #36454F;
        color: white;
        opacity: 0;
        animation: 500ms fadeOnly 1s cubic-bezier(.73,0,.27,1) forwards;
    }

    #submit:hover {
        background: white;
        color: gray;
    }

    .submit {
        display: flex;
        align-content: center;
        justify-content: center;
        width: 100%;
    }

    .content {
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        z-index: 0;
    }

    .back {
        line-height: 0;
        animation: 500ms moveleft3 .1s cubic-bezier(.73,0,.27,1) forwards;
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
<div class="background"></div>

<div class="content">
    <div class="back" onclick="returnPrevious()">➤</div>
    <div class="container">
        <h1><?php echo htmlspecialchars($movieName); ?></h1>
        <div class="forms">
            <div class="showtimes-container">
                <form action="ticket.php" id="timeSubmit" method="get">
                    <input type="hidden" value="<?php echo $poster ?>" name="poster">
                    <input type="hidden" value="<?php echo htmlspecialchars($movieName); ?>" name="movie">
                    <input type="hidden" value="<?php echo htmlspecialchars($movieid); ?>" name="movieid">
                    <input type="hidden" value="<?php echo $acc_type ?>" name="acc_type">
                    <input type="hidden" value="<?php echo $acc_key ?>" name="acc_key">
                    <input type="hidden" value="<?php echo $acc_id ?>" name="acc_id">
                    <input type="hidden" value="<?php echo $return; ?>" name="return_url">

                    <?php
                    foreach ($showtimes as $time) {
                        echo "<input type='hidden' name='showtimes[]' value='" . htmlspecialchars($time) . "'>";
                    }
                    ?>
                    <?php
                    foreach ($showtimesID as $timeID) {
                        echo "<input type='hidden' name='showtimesID[]' value='" . htmlspecialchars($timeID) . "'>";
                    }
                    ?>
                    <?php
                    foreach (array_combine($showtimesID, $showtimes) as $timeID => $time) {
                        $checked = ($_GET['showtime_id'] == $timeID) ? 'checked' : '';
                        echo "<label><input type='radio' name='showtime_id' value='$timeID' onclick='submitForm()' $checked><span class='time'>$time</span></label>";
                    }
                    ?>
                </form>
            </div>
            <br>
            <form action="payment.php" method="get" id="form">
                <input type="hidden" value="<?php echo $poster ?>" name="poster">
                <input type="hidden" value="<?php echo $acc_type ?>" name="acc_type">
                <input type="hidden" value="<?php echo $acc_key ?>" name="acc_key">
                <input type="hidden" value="<?php echo $acc_id ?>" name="acc_id">
                <input type="hidden" value="<?php echo htmlspecialchars($movieName); ?>" name="movie">
                <input type="hidden" value="<?php echo htmlspecialchars($movieid); ?>" name="movieid">
                <input type="hidden" value="<?php echo htmlspecialchars($showtime_id); ?>" name="showtime_id">
                <input id="seats" name="seatsNumber" type="hidden">
                <?php echo generateSeatCheckboxes($numSeats, $selectedSeats); ?>
                <div class="submit">
                    <input type="submit" id="submit" value="Reserve!">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<script>
    var radios = document.querySelectorAll('input[type=radio]');

    radios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.getElementById('timeSubmit').submit();
        });
    });

    document.getElementById('form').addEventListener('submit', function (event) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        var checkedCount = 0;
        var radioButtons = document.getElementsByName("showtime_id");
        var isChecked = false;

        for (var i = 0; i < radioButtons.length; i++) {
            if (radioButtons[i].checked) {
                isChecked = true;
                break;
            }
        }

        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                checkedCount++;
            }
        }

        if (checkedCount === 0) {
            alert('Please select at least one seat.');
            event.preventDefault();
        }
        if (!isChecked) {
            alert("Please select a schedule.");
            event.preventDefault();
        } else {
            document.getElementById('seats').value = checkedCount;
        }
    });

    window.addEventListener('unload', function (event) {
        document.getElementById('form').reset();
    }, false);

    function returnPrevious() {
        window.location.href = '<?php echo $return ?>';
    }
</script>
</html>
