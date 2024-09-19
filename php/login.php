<?php
session_start();

include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['selection'];

    if ($role == 'admin') {
        $sql = "SELECT * FROM admin WHERE staff_username = ?";
    } else {
        $sql = "SELECT * FROM user WHERE Username = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // if (($role == 'admin' && $password == $user['staff_password']) || ($role == 'user' && $password == $user['password'])) {
        if(($role == 'admin' && $password == $user['staff_password']) || ($role == 'user' && password_verify($password, $user['password']))) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if ($role == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "No user found with this username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARK-iN Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/up.png" type="png" />
</head>
<body>
    <section id="login-sect" class="login-sect">
        <div class="container">
            <fieldset>
                <img src="../img/down.png" alt="logo">
                <h1>Login</h1>
                <form action="login.php" method="post">
                    <input type="text" name="username" id="username" placeholder="Enter your username" required>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <label for="checkbox"><input type="checkbox" name="checkbox" id="checkbox">Show Password</label>
                    <div class="radio">
                        <label for="user"><input type="radio" name="selection" id="user" value="user" checked>User</label>
                        <label for="admin"><input type="radio" name="selection" id="admin" value="admin">Admin</label>
                    </div>
                    <input type="submit" value="Log In">
                </form>
                <?php
                if (isset($error)) {
                    echo "<p style='color: red;'>$error</p>";
                }
                ?>
                <p>Don't have an account? <a href="register.php"><br>Click Here</a></p>
                <div class="copyright"><p>Copyright © 2024 PARK-iN. All rights reserved.</p></div>
            </fieldset>
        </div>
    </section>
</body>
<script>
    // check password
    var password = document.getElementById("password");
    var checkbox = document.getElementById("checkbox");

    checkbox.addEventListener("click", function() {
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
    });
</script>

</html>