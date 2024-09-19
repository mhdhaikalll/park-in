<?php
include 'dbconn.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
// Fetch booking data with optional search
$search = '';
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}


// Fetch user data
$sql_users = "SELECT user_id, username, phonenum, email FROM user where user_id LIKE '%$search%' OR username LIKE '%$search%' OR phonenum LIKE '%$search%' OR email LIKE '%$search%'";
$result_users = $conn->query($sql_users);

if (!$result_users) {
    die("Query failed: " . $conn->error);
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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
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

        .table-user {
            margin-top: 5vh;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
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

        input[type="email"], input[type="text"] {
            padding: 10px;
            margin: 10px;
            border-radius: 25px;
        }
        
        .search-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            width: 50%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            /* outline: none; */
            /* font-size: 16px; */
        }

        .search-container button {
            padding: 10px;
            border: 1px solid #ccc;
            border-left: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 0 25px 25px 0;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #45a049;
        }

        .copyright p {
            text-align: center;
            color: #2c2c2c;
            font-size: 10px;
            margin-top: 55vh;
            position: static;
        }
        .search-bar {
            text-align: center;
            margin-top: 20px;
        }

        .search-bar input[type="search"] {
            width: 50%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .search-bar button {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #45a049;
        }
    </style>
    <title>PARK-iN Admin Dashboard</title>
</head>

<body>
    <div>
        <!-- The sidebar -->
        <div class="sidebar">
            <a href="admin_dashboard.php">Home</a>
            <a class="active" href="admin_view_data.php">View Data</a>
            <a href="admin_view_booking.php">View Booking</a>
            <a href="admin_parking.php">View Parking</a>
            <a href="admin_profile.php">Profile</a>
            <a href="logout.php">Log Out</a>
        </div>

        <!-- Page content -->
        <div class="content">
            <h1>Collection of User Data</h1>
            <!-- <div class="search-container">
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for users...">
                <button onclick="searchTable()"><i class="fa fa-search"></i></button>
            </div> -->
            <!-- Search Bar -->
            <div class="search-bar">
                <form method="get" action="admin_view_data.php">
                    <input type="search" name="search" placeholder="Search by username, date or status" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <div class="table-user">
                <table id="userTable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_users->num_rows > 0) : ?>
                            <?php while ($row = $result_users->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phonenum']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <button class="btn" onclick="openModal(<?php echo $row['user_id']; ?>)">Details</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="copyright">
                <p>Copyright © 2024 PARK-iN. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>User Details</h2>
            <form id="userForm">
                <input type="hidden" id="userId" name="user_id" value="">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" readonly>
                </div>
                <div>
                    <label for="phonenum">Phone Number:</label>
                    <input type="text" id="phonenum" name="phonenum">
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>
                <div>
                    <button type="button" class="btn" onclick="editUser()">Edit</button>
                    <button type="button" class="btn btn-danger" onclick="deleteUser()">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(userId) {
            const modal = document.getElementById("userModal");
            const form = document.getElementById("userForm");

            fetch(`get_user.php?id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    form.user_id.value = data.user_id;
                    form.username.value = data.username;
                    form.phonenum.value = data.phonenum;
                    form.email.value = data.email;
                    modal.style.display = "block";
                });
        }

        function closeModal() {
            const modal = document.getElementById("userModal");
            modal.style.display = "none";
        }

        function editUser() {
            const form = document.getElementById("userForm");
            const formData = new FormData(form);

            fetch('edit_user.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("User updated successfully!");
                        location.reload();
                    } else {
                        alert("Failed to update user.");
                    }
                });
        }

        function deleteUser() {
            if (confirm("Are you sure you want to delete this user?")) {
                const userId = document.getElementById("userId").value;

                fetch(`delete_user.php?id=${userId}`, {
                    method: 'GET'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("User deleted successfully!");
                            location.reload();
                        } else {
                            alert("Failed to delete user.");
                        }
                    });
            }
        }

        function searchTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("userTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let tdArray = tr[i].getElementsByTagName("td");
                let showRow = false;

                for (let j = 0; j < tdArray.length - 1; j++) { // exclude Actions column
                    if (tdArray[j]) {
                        let tdValue = tdArray[j].textContent || tdArray[j].innerText;
                        if (tdValue.toLowerCase().indexOf(filter) > -1) {
                            showRow = true;
                            break;
                        }
                    }
                }

                if (showRow) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    </script>
</body>

</html>
