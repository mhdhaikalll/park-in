<?php
include 'dbconn.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT user_id, username, phonenum, email FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    $stmt->close();
}
$conn->close();
?>
