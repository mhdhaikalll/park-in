<?php
session_start();
include 'dbconn.php'; // Ensure this file contains your database connection details

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$user_id_result = $conn->query("SELECT user_id FROM user WHERE username='$username'");
$user_id_row = $user_id_result->fetch_assoc();
$user_id = $user_id_row['user_id'];
// Fetch booking history for the logged-in user
$sql = "SELECT b.booking_id, p.location, b.book_date, b.status, t.amount, t.status as payment_status
        FROM booking b
        JOIN parking p ON b.park_id = p.park_id
        LEFT JOIN transaction t ON b.booking_id = t.booking_id
        WHERE b.user_id = ?
        ORDER BY b.book_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

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
        .content h1 span{
            color: #ffd900;
        }
        .table-content{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 5vh;
        }
        table, td, th, tr{
            padding: 20px;
            border: 1px solid #eaeaea;
            border-collapse: collapse;
        }
        th{
            background-color: #dadada;
        }
        table{
            margin-top: 2vh;
            background-color: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            width: fit-content;
            height: fit-content;
        }
        th, td{
            text-align: center;
        }
        .copyright p {
            text-align: center;
            color: #2c2c2c;
            font-size: 10px;
            margin-top: 55vh;
            position: static;
        }
    </style>
    <title>PARK-iN User Dashboard</title>
</head>

<body>
    <div>
        <!-- The sidebar -->
        <div class="sidebar">
            <a href="user_dashboard.php">Home</a>
            <a href="make_booking.php">Make Booking</a>
            <a class="active" href="view_booking.php">View Booking</a>
            <a href="logout.php">Log Out</a>
        </div>

        <!-- Page content -->
        <div class="content">
            <h1>Booking <span>History</span></h1>
            <div class="table-content">
                <table>
                    <tr>
                        <th>Booking ID</th>
                        <th>Parking Location</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                    </tr>
                    <?php
                    if($result -> num_rows > 0) {
                        while($row = $result -> fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['booking_id'] . "</td>";
                            echo "<td>" . $row['location'] . "</td>";
                            echo "<td>" . $row['book_date'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td>" . ($row['amount'] ? $row['amount'] : 'N/A') . "</td>";
                            echo "<td>" . ($row['payment_status'] ? $row['payment_status'] : 'N/A') . "</td>";
                            echo "</tr>";
                        } 
                    } else {
                            echo "<tr><td colspan='6'>No bookings found</td></tr>";
                        }
                    ?>
                </table>
            </div>
            <div class="copyright">
                <p>Copyright © 2024 PARK-iN. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>