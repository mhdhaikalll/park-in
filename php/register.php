<?php
session_start();

include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phonenum'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or phone number already exists
    $sql = "SELECT * FROM user WHERE Username = ? OR phonenum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $phoneNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username or phone number already exists.";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO user (phonenum, Username, Password, Email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $phoneNumber, $username, $hashedPassword, $email);
        // $stmt->bind_param("ssss", $phoneNumber, $username, $password, $email);

        if ($stmt->execute()) {
            $success = "Registration successful. You can now <a href='login.php'>log in</a>.";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARK-iN Register</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/up.png" type="png" />
</head>
<body>
    <section id="register-sect" class="register-sect">
        <div class="container">
            <fieldset>
                <img src="../img/down.png" alt="logo">
                <h1>Register</h1>
                <form action="register.php" method="post">
                    <input type="text" name="username" id="username" placeholder="Create username">
                    <input type="password" name="password" id="password" pattern=".{8,}" title="Password must be at least 8 character long" placeholder="Create password">
                    <input type="email" name="email" id="email" placeholder="Enter your email">
                    <input type="tel" name="phonenum" id="phonenum" placeholder="Enter your phone number">
                    <label for="checkbox"><input type="checkbox" name="checkbox" id="checkbox">Show Password</label>
                    <input type="submit" value="Sign Up">
                </form>
                <?php
                if (isset($error)) {
                    echo "<p style='color: red;'>$error</p>";
                }
                if (isset($success)) {
                    echo "<p style='color: green;'>$success</p>";
                }
                ?>
                <p>Already have an account? <a href="login.php"><br>Click Here</a></p>
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
