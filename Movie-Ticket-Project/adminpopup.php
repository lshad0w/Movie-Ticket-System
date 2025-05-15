<?php 

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

?>

<!DOCTYPE html>
<html>
<head>
    <title>Ticket buying login page</title>
    <link rel="stylesheet" href="adminpop.css"> <!-- Adjust your CSS link accordingly -->
</head>
<body>

<div id="mydiv">
    <form action="moviemenu.php" method="post">
        <!-- Add session variables as hidden inputs -->
        <input type="hidden" value="<?php echo $acc_type?>" name="acc_type">
        <input type="hidden" value="<?php echo $acc_key?>" name="acc_key">
        <input type="hidden" value="<?php echo $acc_id?>" name="acc_id">
        <input type="image" style="height: 35px; width: 35px; border-radius: 12px;" src="img/gear.png">
    </form>
</div>
    <script>//script to move the admin menu icon implement lang sa mismong movie site
      dragElement(document.getElementById("mydiv"));
  
  function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById(elmnt.id + "header")) {
      // if present, the header is where you move the DIV from:
      document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
    } else {
      // otherwise, move the DIV from anywhere inside the DIV:
      elmnt.onmousedown = dragMouseDown;
    }
  
    function dragMouseDown(e) {
      e = e || window.event;
      e.preventDefault();
      // get the mouse cursor position at startup:
      pos3 = e.clientX;
      pos4 = e.clientY;
      document.onmouseup = closeDragElement;
      // calls the function whenever you move the element:
      document.onmousemove = elementDrag;
    }
  
    function elementDrag(e) {
      e = e || window.event;
      e.preventDefault();
      // calculate the new cursor position:
      pos1 = pos3 - e.clientX;
      pos2 = pos4 - e.clientY;
      pos3 = e.clientX;
      pos4 = e.clientY;
      // set the gear icon to desired location:
      elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
      elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }
  
    function closeDragElement() {
      //to stop movement when mouse is released:
      document.onmouseup = null;
      document.onmousemove = null;
    }
  }
  
  
      </script>



  </html>