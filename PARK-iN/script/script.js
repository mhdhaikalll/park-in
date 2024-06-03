
// check password
var password = document.getElementById("password");
var checkbox = document.getElementById("checkbox");

checkbox.addEventListener("click", function() {
  if (password.type === "password") {
    password.type = "text";
  } else {
    password.type = "password";
  }
});

// Get all elements with the class "box"
let boxes = document.getElementsByClassName("box");

function changeColor() {
  // Loop through each "box" element
  for (let i = 0; i < boxes.length; i++) {
    // Check the current color and change it
    if (boxes[i].style.backgroundColor === "rgb(29, 133, 25)") {
      boxes[i].style.backgroundColor = "red";
    } else {
      boxes[i].style.backgroundColor = "rgb(29, 133, 25)";
    }
  }
}

/* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
  document.getElementById("main").style.marginLeft = "250px";
  document.body.style.backgroundColor = "rgba(5,5,5)";
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("main").style.marginLeft = "0";
  document.body.style.backgroundColor = "black";
}

//userpage
document.addEventListener('DOMContentLoaded', (event) => {
  let button = document.querySelector(".book");
  button.addEventListener("click", function() {
      window.location.href = "make_booking.html";
  });
});