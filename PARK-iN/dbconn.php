<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "park_in";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection Error: " . $conn->connect_error);
}

?> 