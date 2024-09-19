<?php
include 'dbconn.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch parking data
$sql_parking = "SELECT * FROM parking";
$result_parking = $conn->query($sql_parking);

if (!$result_parking) {
    die("Query failed: " . $conn->error);
}

// Handle form submission to add new parking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_parking'])) {
    $id = $_POST['park_id'];
    $location = $_POST['location'];
    
    // Insert into database
    $sql_insert = "INSERT INTO parking (park_id, location) VALUES ('$id','$location')";
    if ($conn->query($sql_insert) === TRUE) {
        // Redirect to refresh the page after insertion
        header("Location: admin_parking.php");
        exit();
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}

// Handle form submission to edit parking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_parking'])) {
    $id = $_POST['edit_park_id'];
    $location = $_POST['edit_location'];

    // Update database
    $sql_update = "UPDATE parking SET location='$location' WHERE park_id='$id'";
    if ($conn->query($sql_update) === TRUE) {
        // Redirect to refresh the page after update
        header("Location: admin_parking.php");
        exit();
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_park_id'])) {
    $id = $_POST['delete_park_id'];

    // Delete from database
    $sql_delete = "DELETE FROM parking WHERE park_id='$id'";
    if ($conn->query($sql_delete) === TRUE) {
        // Redirect to refresh the page after deletion
        header("Location: admin_parking.php");
        exit();
    } else {
        echo "Error: " . $sql_delete . "<br>" . $conn->error;
    }
}

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

        .parking-table {
            margin-top: 5vh;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .add-parking-form {
            margin-top: 5vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .add-parking-form input {
            padding: 8px;
            margin-right: 10px;
        }

        .add-parking-form button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-parking-form button:hover {
            background-color: #45a049;
        }

        .btn {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn-danger {
            background-color: #f44336;
        }

        .btn-danger:hover {
            background-color: #da190b;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
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

        .content h1 {
            text-align: center;
            margin-top: 5vh;
        }

        input[type="text"] {
            padding: 10px;
            margin: 10px;
            border-radius: 25px;
        }

        .copyright p {
            text-align: center;
            color: #2c2c2c;
            font-size: 10px;
            margin-top: 55vh;
            position: static;
        }

        .content h1{
            text-align: center;
            margin-top: 5vh;
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
            <a class="active" href="admin_parking.php">View Parking</a>
            <a href="admin_profile.php">Profile</a>
            <a href="logout.php">Log Out</a>
        </div>

        <!-- Page content -->
        <div class="content">
            <h1>Collection of Parking Data</h1>
            <div class="parking-table">
                <table>
                    <thead>
                        <tr>
                            <th>Park ID</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_parking->num_rows > 0) : ?>
                            <?php while ($row = $result_parking->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['park_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td>
                                        <button class="btn" onclick="openModal('<?php echo $row['park_id']; ?>', '<?php echo htmlspecialchars($row['location']); ?>')">Details</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3">No parking details found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="add-parking-form">
                <h2>Add Parking</h2>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <input type="text" name="park_id" placeholder="Parking ID" required>
                    <input type="text" name="location" placeholder="Location" required>
                    <button type="submit" name="add_parking">Add Parking</button>
                </form>
            </div>
            <div class="copyright">
                <p>Copyright © 2024 PARK-iN. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Parking Details</h2>
            <form id="detailsForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" name="edit_park_id" id="edit_park_id">
                <label for="edit_location">Location:</label>
                <input type="text" name="edit_location" id="edit_location" required>
                <br>
                <button type="submit" name="edit_parking" class="btn">Save Changes</button>
                <button type="button" onclick="deleteParking()" class="btn btn-danger">Delete Parking</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, location) {
            document.getElementById('edit_park_id').value = id;
            document.getElementById('edit_location').value = location;
            document.getElementById('detailsModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        function deleteParking() {
            if (confirm("Are you sure you want to delete this parking?")) {
                const form = document.getElementById('detailsForm');
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_park_id';
                deleteInput.value = document.getElementById('edit_park_id').value;
                form.appendChild(deleteInput);
                form.submit();
            }
        }


        window.onclick = function(event) {
            const modal = document.getElementById('detailsModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>
