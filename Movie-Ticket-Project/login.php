<!DOCTYPE html>
<html>
<head>
    <title>Ticket buying login page</title>
    <style>
      body{
        height: 100%;
        margin: 0 auto;
        padding: 0;      
      }
      #gradient1{
        overflow: auto; /* para sa scrool feature */
        position: fixed; 
        z-index: 1; /* Sit on top */
        width: 100%; 
        height: 100%; 
        background-image: url(poster/background.png);
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

      .box{
        background-color: white;
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        width: 100%;
        height: 90%;
        overflow: hidden;
        background: none;
      }

      h1{
        color: white;
        font-size: 20px;
        margin: 20;
        padding: 0;
        font-family: spartan;
        font-size: 30px;
      }

      form {
        border: 3px solid #f1f1f1;
        font-family: spartan;
      }

      input[type=text], input[type=password] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 0px solid white;
        box-sizing: border-box;
        transition-duration: 500ms;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
      }
      input[type=text]:hover, input[type=password]:hover {
        border-color: #04aa6d; 
      }

      button {
        background-color: #06ac06; color: grey;
        color: white;
        border-color: #000;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        font-family: Arial, Helvetica, sans-serif;
        font-style: bold;
        width: 30%;
        transition-duration: 500ms;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
      }

      button:hover {
        opacity: 0.8;
        background-color: green;
      }
      button.first {
        font-size: 20px;
        width: 200px;
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24);
        color: #36454F;
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;

      }
      button.first:hover {
        transform: scale(1);
        opacity: 0.8;
        color: white;
        background-color: gray;
        box-shadow: 0 12px 16px 5 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
      }
      button.cancelbtn {
        background-color: #f44336;
        color: white;
      }
      button.cancelbtn:hover {
        background-color: #f44336c7;
      }

      .imgcontainer {
        text-align: center;
        margin: 24px 0 12px 0;
      }

      img.icon {
        width: 20%;
        border-radius: 50%;
      }
      .container {
        padding: 16px;
        font-family: spartan;
      }
      .container1 {
        text-align:center;
      }

      span.psw {
        padding-left: 16px;
      }

      @media screen and (max-width: 300px) {
        span.psw {
          display: block;
          float: none;
        }
        .cancelbtn {
          width: 100%;
        }
      }

      .modal {
        overflow: auto;
        display: none;
        position: fixed; 
        z-index: 1;
        width: 100%; 
        height: 100%; 
        background-color: rgba(0,0,0,0.4); 
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        overflow: hidden;
      }

      .modal-content {
        position:relative;  
        background-color: #fefefe;
        padding-top: 50px;
        border: 1px solid #888;
        width: 400px; 
      }

      .close {
        position: absolute;
        right: 25px;
        top: 0;
        color: #000;
        font-size: 35px;
        font-weight: bold;
      }

      .close:hover,.close:focus {
        color: red;
        cursor: pointer;
      }

      .animateZoomout {
        -webkit-animation: animatezoom 500ms;
        animation: animatezoom 500ms
      }

      @-webkit-keyframes animatezoom {
        from {-webkit-transform: scale(0)}
        to {-webkit-transform: scale(2)}
      }

      @keyframes animatezoom {
        from {transform: scale(0)}
        to {transform: scale(1)}
      }

      .background {
        position: absolute;
        background-image: url(poster/background.jpg);
        background-position: center;
        background-size: cover;
        filter: brightness(30%);
        width: 100%;
        height: 100%;
      }
    </style>
    <script>
        function showPass() {
          var i = document.getElementById("passw");
          var j = document.getElementById("adpassw");
          if (i.type === "password" || j.type === "password") {
              i.type = "text";
              j.type = "text";
          } else {
              i.type = "password";
              j.type = "password";
          }
        }

        function refreshForm1() {
          document.forms['Formation'].reset();
        }

        function refreshForm2() {
          document.forms['Formation1'].reset();
        }

        function closeForm(){
          document.getElementById('user').style.display='none';
          document.getElementById('user_admin').style.display='none';
        }
    </script>
</head>


<body>
    <div id="gradient1">
      <div class="box" >
        <div class="container1">
          <h1>LOG-IN AS</h1><center>
            <button class="first" onclick="document.getElementById('user').style.display='flex'">User</button>
            <button class="first" onclick="document.getElementById('user_admin').style.display='flex'">Admin</button></center>
        </div>
      </div>
    </div>
    <!-- popup content -->
    <div id="user" class="modal">
      <span onclick="document.getElementById('user').style.display='none'"
    class="close" title="Close Modal">&times;</span>

      <!-- popup box -->
      <form class="modal-content animateZoomout" name="Formation" id="Formation" method="post" action="login_validation.php" >
        <div class="imgcontainer">
          <img src="img/user.png" alt="Avatar" class="icon">
        </div>

        <div class="container">

          <label for="email"><b>Email</b></label>
          <input type="text"  placeholder="Enter Email" name="email" id="email" required>

          <label for="passw"><b>Password</b></label>
          <input type="password" autocomplete="off" placeholder="Enter Password" name="passw" id="passw"  required>


          <label>
            <input class="checkbox" type="checkbox" onclick="showPass()"> Show Password
            |<span class="psw">Forgot <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">password?</a></span>
        </label>
        </div>

        <div class="container" style="background-color:#f1f1f1"><center>
            <button type="submit">Login</button>
          <button type="button" onclick="refreshForm1();closeForm()"  class="cancelbtn">Cancel</button></center>


        </div>
        <?php 
        if (isset($_GET['error'])&& $_GET['error']== 'info_mismatch'){
          echo "<p style='color: red;'> Invalid username or password. </p>";

         } 
         ?>
      </form>
    </div>


    <!-- The Modal -->
    <div id="user_admin" class="modal">
      <span onclick="document.getElementById('user_admin').style.display='none'"
    class="close" title="Close Modal">&times;</span>

      <!-- Modal Content -->
       <!-- action should direct sa mismong ticket buying website -->
      <form class="modal-content animateZoomout" name="Formation1" id="Formation1" action="login_validation_admin.php" method="post" >
        <div class="imgcontainer">
          <img src="img/admin.png" alt="Avatar" class="icon">
        </div>

        <div class="container">
          <label for="adname"><b>Admin key</b></label>
          <input type="text" placeholder="Enter Admin key" name="adname" id="adname" required>

          <label for="psw"><b>Password</b></label>
          <input type="password"  autocomplete="off"  placeholder="Enter Password" name="adpassw" id="adpassw" required>


          <label>
            <input class="checkbox" type="checkbox" onclick="showPass()"> Show Password
            |<span class="psw">Forgot <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">password?</a></span>
          </label>
        </div>

        <div class="container" style="background-color:#f1f1f1"><center>
          <button type="submit">Login</button>
          <button type="button" onclick="refreshForm2();closeForm();SignIn()" class="cancelbtn">Cancel</button></center>

        </div>
      </form>
    </div>
    <div class="background"></div>   
</body>
<script src="script.js"></script>
<script>
    var modal = document.getElementById('user');
    var modal2 = document.getElementById('user_admin');

    // if you click outside the box it will close it
    window.onclick = function(event) {
      if (event.target == modal || event.target==modal2) {
        modal.style.display = "none";
        modal2.style.display = "none";
        refreshForm1();
        refreshForm2();
      }
    }
</script>
</html>
