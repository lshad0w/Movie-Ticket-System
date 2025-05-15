function passCheck() {
  if (Formation.userid.value == "admin" && Formation.passw.value == "root") {
    document.location.href = "menu.html";
  } else {
    alert("Account does not exist");
    event.preventDefault();
    return false;
  }
}

function validation() {}

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

function refreshForm() {
  document.forms["Formation"].reset();
}

function closeForm() {
  document.getElementById("user").style.display = "none";
  document.getElementById("user_admin").style.display = "none";
}

function onEvent(event) {
  if (event.key === "Enter") {
    e.preventDefault();
    alert("Enter was pressed was pressed");
  }
}
