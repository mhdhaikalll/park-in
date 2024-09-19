<?php
session_start();
include "dbconn.php";

// Check if the user is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch user data from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM admin WHERE staff_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
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

        .info-box {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
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
        .card {
      position: relative;
      width: 500px;
      height: 550px;
      border-radius: 20px;
      z-index: 1111;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      margin-top: 5vh;
      margin-bottom: 5vh;
      background: #eaeaea;
      box-shadow: 20px 20px 60px #b9b9b9,
        -20px -20px 60px #ffffff;
    }

    .bg {
      position: absolute;
      top: 5px;
      left: 5px;
      width: 490px;
      height: 540px;
      z-index: 2;
      background: rgba(255, 255, 255, .95);
      backdrop-filter: blur(24px);
      border-radius: 10px;
      overflow: hidden;
      outline: 2px solid white;
    }

    .blob {
      position: absolute;
      z-index: 1;
      top: 50%;
      left: 50%;
      width: 470px;
      height: 470px;
      border-radius: 50%;
      background-color: #ffd900;
      opacity: 1;
      filter: blur(12px);
      animation: blob-bounce 2s infinite ease-in;
    }

    .user-details {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    @keyframes blob-bounce {
      0% {
        transform: translate(-100%, -100%) translate3d(0, 0, 0);
      }

      25% {
        transform: translate(-100%, -100%) translate3d(100%, 0, 0);
      }

      50% {
        transform: translate(-100%, -100%) translate3d(100%, 100%, 0);
      }

      75% {
        transform: translate(-100%, -100%) translate3d(0, 100%, 0);
      }

      100% {
        transform: translate(-100%, -100%) translate3d(0, 0, 0);
      }
    }

    .bg .details {
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 1em;
      padding: 10px 10%;
      margin-top: 15%;
    }

    .details h1 {
      text-align: center;
    }

    .details span{
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      margin-bottom: 2vh;
      margin-top: 2em;
    }
    table,
    th,
    td,
    tr {
      padding: 5px;
      text-align: start;
    }

    table {
      margin-top: 3vh;
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
            <a href="admin_dashboard.php">Home</a>
            <a href="admin_view_data.php">View Data</a>
            <a href="admin_view_booking.php">View Booking</a>
            <a href="admin_parking.php">View Parking</a>
            <a class="active" href="admin_profile.php">Profile</a>
            <a href="logout.php">Log Out</a>
        </div>

        <!-- Page content -->
        <div class="content">
            <div class="user-details">
                <div class="card">
                    <div class="bg">
                        <div class="details">
                            <h1>Admin Details</h1>
                            <span><i class="fa-solid fa-user" style="font-size: 64px"></i></span>
                            <table>
                                <tr>
                                    <th>Admin ID</th>
                                    <td><?php echo htmlspecialchars($user['staff_id']); ?></td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td><?php echo htmlspecialchars($user['staff_username']); ?></td>
                                </tr>
                                <tr>
                                    <th>Admin Name</th>
                                    <td><?php echo htmlspecialchars($user['staff_name']); ?></td>
                                </tr>
                                <!-- <tr>
                                    <th>Phone Number</th>
                                    <td><?php echo htmlspecialchars($user['phonenum']); ?></td>
                                </tr> -->
                            </table>
                        </div>
                    </div>
                    <div class="blob"></div>
                </div>
            </div>
            <div class="copyright">
                <p>Copyright © 2024 PARK-iN. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>