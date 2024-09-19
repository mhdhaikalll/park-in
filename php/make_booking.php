<?php
include 'dbconn.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id_result = $conn->query("SELECT user_id FROM user WHERE username='$username'");
$user_id_row = $user_id_result->fetch_assoc();
$user_id = $user_id_row['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['make_booking'])) {
    $park_id = $_POST['park_id'];
    $book_date = $_POST['book_date'];

    $sql = "INSERT INTO booking (user_id, park_id, book_date, status) VALUES ('$user_id', '$park_id', '$book_date', 'booked')";
    
    if ($conn->query($sql) === TRUE) {
        $booking_id = $conn->insert_id;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay'])) {
    $booking_id = $_POST['booking_id'];
    $amount = 150; // RM150 per lot

    $sql = "INSERT INTO transaction (booking_id, amount, status) VALUES ('$booking_id', '$amount', 'paid')";
    
    if ($conn->query($sql) === TRUE) {
        $transac_id = $conn->insert_id;
        
        // Redirect to generate_receipt.php with the transaction details
        header("Location: generate_receipt.php?transac_id=$transac_id&booking_id=$booking_id&amount=$amount");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

$sql = "SELECT * FROM parking";
$result = $conn->query($sql);
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
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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
        .copyright p {
            text-align: center;
            color: #2c2c2c;
            font-size: 10px;
            margin-top: 45vh;
        }
        table, tr, td, th{
            /* border: 1px solid #2c2c2c; */
            padding: 30px;
            text-align: left;
            border-collapse: collapse;
        }
        .booking{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            margin-top: 5vh;
            height: 70%;
            width: max-content;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        input[type="date"]{
            padding: 10px;
            margin-left: 20%;
            cursor: pointer;
        }
        input[type="submit"]{
            padding: 10px;
            width: 100%;
            font-size: 18px;
            background-color: #fefefe;
            cursor: pointer;
            border-radius: 25px;
        }
        input[type="submit"]:hover{
            background-color: #ffd900;
        }
        select, option{
            padding: 10px;
            cursor: pointer;
        }        
        /* modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 15px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation-name: animatetop;
            animation-duration: 0.4s;
        }
        form p{
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 18px;
            padding: 10px;
        }
        .modal-button{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 5vh;
        }
        #myBtn{
            width: 200%;
            height: 40px;
            background-color: #fefefe;
            font-size: 18px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        #myBtn:hover{
            background-color: #ffd900;
        }
        @keyframes animatetop {
            from {top:-300px; opacity:0}
            to {top:0; opacity:1}
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .content h1{
            text-align: center;
            margin-top: 5vh;
        }
        .content h1 span{
            color:#ffd900;
        }
    </style>
    <title>PARK-iN User Dashboard</title>
</head>

<body>
    <div>
        <!-- The sidebar -->
        <div class="sidebar">
            <a href="user_dashboard.php">Home</a>
            <a class="active" href="make_booking.php">Make Booking</a>
            <a href="view_booking.php">View Booking</a>
            <a href="logout.php">Log Out</a>
        </div>

        <!-- Page content -->
        <div class="content">
            <h1>Make <span>Booking</span></h1>
            <div class="booking">
                <form method="post" action="make_booking.php">
                    <table>
                        <tr>
                            <th>Select Parking Lot</th>
                            <td>
                                <select name="park_id" required>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['park_id'] . "'>" . $row['park_id'] . " - " . $row['location'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No Parking Lots Available</option>";
                                }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Select Date</th>
                            <td><input type="date" name="book_date" id="book_date" required></td>
                        </tr>
                        <tr>
                            <th colspan="2"><input type="submit" name="make_booking" value="Book"></th>
                        </tr>
                    </table>
                </form>
            </div>
            <?php if (isset($booking_id)) { ?>
                <!-- Trigger/Open The Modal -->
                <div class="modal-button">
                    <button id="myBtn">Pay Now</button>
                </div>
                
                <!-- The Modal -->
                <div id="myModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <form method="post" action="make_booking.php">
                            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                            <p>Booking ID: <?php echo $booking_id; ?></p>
                            <p>Amount: RM150</p>
                            <input type="submit" name="pay" value="Pay" id="pay-btn">
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="copyright">
                <p>Copyright © 2024 PARK-iN. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        //Get the payment button
        var btn2 = document.getElementById("pay-btn")

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        btn2.onclick = function() {
            window.location.href = "after_payment.php"
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>
