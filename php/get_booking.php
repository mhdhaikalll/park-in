<?php
include 'dbconn.php';

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $sql = "SELECT booking_id, username, book_date, status FROM booking INNER JOIN user ON booking.user_id = user.user_id WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Booking not found']);
    }
    $stmt->close();
}
$conn->close();
?>
