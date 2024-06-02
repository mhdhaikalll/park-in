<?php
session_start();

include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phoneNumber = $_POST['phonenum'];
    $platNumber = $_POST['platnum'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username or phone number already exists
    $sql = "SELECT * FROM user WHERE Username = ? OR PhoneNumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $phoneNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username or phone number already exists.";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO user (PhoneNumber, Username, Password, Email, PlatNumber) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $phoneNumber, $username, $password, $email, $platNumber);

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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <section id="register-sect" class="register-sect">
        <div class="container">
            <fieldset>
                <h1>Register</h1>
                <form action="register.php" method="post">
                    <input type="tel" name="phonenum" id="phonenum" placeholder="Enter your phone number" required>
                    <input type="text" name="platnum" id="platnum" placeholder="Enter your plat number" required>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    <input type="text" name="username" id="username" placeholder="Create username" required>
                    <input type="password" name="password" id="password" pattern=".{8,}" placeholder="Create password" required>
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
            </fieldset>
        </div>
    </section>
</body>
</html>
