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

        .content .title {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100%;
            margin-top: 30%;
        }

        .title h1 {
            font-size: 42px;
        }

        .title h1 span {
            color: #ffd900;
        }

        .text {
            margin-top: 2vh;
        }

        .text a {
            text-align: center;
            font-size: 12px;
            text-decoration: none;
            color: inherit;
        }

        .text a:hover {
            color: #ffd900;
            transition: 0.5s;
        }

        .copyright p {
            text-align: center;
            color: #2c2c2c;
            font-size: 10px;
            margin-top: 15vh;
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
            <div class="title">
                <h1>Payment <span>successful!</span></h1>
            </div>
            <div class="copyright">
                <p>Copyright © 2024 PARK-iN. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>