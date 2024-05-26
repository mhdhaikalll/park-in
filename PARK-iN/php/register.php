<?php
    include "dbconn.php";
    $phonenum = $_POST["PhoneNumber"];
    $username = $_POST["Username"];
    $password = $_POST["Passsword"];
    $email = $_POST["Email"];
    $platnum = $_POST["PlatNumber"];

    $sql = "INSERT INTO user (PhoneNumber, Username, Password, Email, PlatNumber) VALUES ('$phonenum','$username','$password','$email','$platnum')";

    $sendquery = mysqli_query($conn, $sql);

    if ($sendquery) {
        // Display success message using JavaScript alert
        echo '<script>alert("Data successfully inserted");</script>';
        // Redirect to the initial display after alert
        echo '<script>window.location.href = "index.html";</script>';
    } else {
        // Display error message using JavaScript alert
        echo '<script>alert("Failed to insert data");</script>';
        // Redirect to the initial display after alert
        echo '<script>window.location.href = "index.html";</script>';
    }
?>