<?php
include 'dbconn.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
  }

// Fetch total users
$sql_users = "SELECT COUNT(*) AS total_users FROM user";
$result_users = $conn->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'];

// Fetch total income for today
$sql_income_today = "SELECT SUM(t.amount) AS total_income_today
                     FROM transaction t
                     JOIN booking b ON t.booking_id = b.booking_id
                     WHERE DATE(b.book_date) = CURDATE() AND t.status = 'paid'";
$result_income_today = $conn->query($sql_income_today);
$total_income_today = $result_income_today->fetch_assoc()['total_income_today'];

// Fetch total revenue for the current year
$current_year = date("Y");
$sql_revenue_year = "SELECT SUM(t.amount) AS total_revenue_year
                     FROM transaction t
                     JOIN booking b ON t.booking_id = b.booking_id
                     WHERE YEAR(b.book_date) = $current_year AND t.status = 'paid'";
$result_revenue_year = $conn->query($sql_revenue_year);
$total_revenue_year = $result_revenue_year->fetch_assoc()['total_revenue_year'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="icon" href="../img/up.png" type="png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap");

        /* basic */
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
            background: #eaeaea;
            color: #2c2c2c;
            font-size: 18px;
        }

        .sidebar {
            margin: 0;
            padding: 0;
            width: 200px;
            background-color: #eaeaea;
            position: fixed;
            height: 100%;
            overflow: auto;
            box-shadow: 2px 2px 10px #4a4a4a;
        }

        .sidebar a {
            display: block;
            color: #2c2c2c;
            padding: 16px;
            text-decoration: none;
        }

        .sidebar a.active {
            background-color: #4a4a4a;
            color: #eaeaea;
        }

        .sidebar a:hover:not(.active) {
            background-color: #555;
            color: #eaeaea;
            transition: 0.3s;
        }

        div.content {
            margin-left: 200px;
            padding: 1px 16px;
            height: 100%;
        }

        @media screen and (max-width: 700px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                float: left;
            }

            div.content {
                margin-left: 0;
            }
        }

        @media screen and (max-width: 400px) {
            .sidebar a {
                text-align: center;
                float: none;
            }
        }

        .content h1{
            text-align: center;
            margin-top: 5vh;
        }

        .info-box {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 5vh;
        }

        .box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 30%;
        }

        .box h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .box p {
            font-size: 18px;
            margin: 0;
        }

        .copyright p {
            text-align: center;
            color: #2c2c2c;
            font-size: 10px;
            margin-top: 55vh;
            position: static;
        }
    </style>
    <title>PARK-iN Admin Dashboard</title>
</head>

<body>
    <div>
        <!-- The sidebar -->
        <div class="sidebar">
            <a class="active" href="admin_dashboard.php">Home</a>
            <a href="admin_view_data.php">View Data</a>
            <a href="admin_view_booking.php">View Booking</a>
            <a href="admin_parking.php">View Parking</a>
            <a href="admin_profile.php">Profile</a>
            <a href="logout.php">Log Out</a>
        </div>

        <!-- Page content -->
        <div class="content">
            <h1>Statistic</h1>
            <div class="info-box">
                <div class="box" id="box1">
                    <h2>Total Users</h2>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="box" id="box2">
                    <h2>Total Income Today</h2>
                    <p><?php echo $total_income_today !== null ? '$' . number_format($total_income_today, 2) : '$0.00'; ?></p>
                </div>
                <div class="box" id="box3">
                    <h2>Total Revenue This Year</h2>
                    <p><?php echo $total_revenue_year !== null ? '$' . number_format($total_revenue_year, 2) : '$0.00'; ?></p>
                </div>
            </div>
            <div class="copyright">
                <p>Copyright © 2024 PARK-iN. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
