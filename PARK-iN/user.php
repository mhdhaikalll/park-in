<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img/estd_2024-removebg-preview.png" type="png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>PARK-iN</title>
</head>
<body>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="side-area">
            <button><a href="make_booking.html">Make Booking</a></button>
            <button><a href="view_booking.html">View Parking</a></button>
            <button><a href="logout.php">Log Out</a></button>
        </div>
    </div>
    
    <!-- Use any element to open the sidenav -->
    <span onclick="openNav()"><i class="fa-sharp fa-solid fa-bars"></i></span>
    
    <!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page -->
    <div id="main">
        <h1>Welcome to <span>PARK-iN</span></h1>
        <p>Safety and efficiency is our motto</p>
        <span><i class="fa-duotone fa-circle-user"></i></span>
        <div class="user-details">
            <h2>Welcome, <span><?php echo htmlspecialchars($user['Username']); ?></span></h2>
            <h2>Email: <?php echo htmlspecialchars($user['Email']); ?></h2>
            <h2>Phone: <?php echo htmlspecialchars($user['PhoneNumber']); ?></h2>
        </div>
    </div>
</body>
<script>
    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
  document.getElementById("main").style.marginLeft = "250px";
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("main").style.marginLeft = "0";
}

//userpage
document.addEventListener('DOMContentLoaded', (event) => {
  let button = document.querySelector(".book");
  button.addEventListener("click", function() {
      window.location.href = "make_booking.html";
  });
});
</script>
</html>