
<!DOCTYPE html>
<html>
<head>
    <title>Ticket buying login page</title>
    <link rel=" stylesheet" href="style.css">
<style>
    body{
    background-color:black;
    }
/* popup box ng forms */
.modal-content.signout {
  position:relative;
  
  background-color: #fefefe;
  padding-top: 50px;
  margin: 80px auto; /*para center yung pop up */
  width: 20%; 
}
.centerMsg{
  color: black;
  font-size: 30px;
  text-align: center;
  margin: 0;
  padding: 0;
  font-family: Arial, Helvetica, sans-serif;
}
.close {
  /* Position it in the top right corner outside of the modal */
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

  .logout-button {
    padding: 10px 20px;
    background-color: white;
    color: black;
    border: solid 2px grey;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-left:30%;

    &:hover {
    background-color: #d32f2f; 
    color:white;
  }
}
.container {
  padding: 16px;
}
        </style>
</head>
<body>
        <div class="modal-content signout">
            <div class="container">
                <span onclick="window.location='index.php'"
                    class="close" title="cancel">&times;</span>
                        <div class="centerMsg">Are you sure?</div>
                            <div class="container">
                                <input class='logout-button' type='button' name="logoutbtn" id='logoutbtn' value='Sign Out' onclick="window.location.href='logout_direct.php';">
                            </div>
            </div>
        </div>
</div>
    </body>

    </html>