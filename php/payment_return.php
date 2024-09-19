<?php
include 'dbconn.php';
session_start();

if (isset($_GET['status_id']) && isset($_SESSION['booking_id'])) {
    $status_id = $_GET['status_id'];
    $booking_id = $_SESSION['booking_id'];
    $amount = $_SESSION['amount'];

    if ($status_id == 1) {
        // Payment successful
        $status = 'paid';
    } else {
        // Payment failed
        $status = 'unpaid';
    }

    // Update booking status in the database
    $sql = "UPDATE booking SET status='$status' WHERE booking_id='$booking_id'";
    if ($conn->query($sql) === TRUE) {
        if ($status == 'paid') {
            header("Location: generate_receipt.php?booking_id=$booking_id");
        } else {
            echo "Payment failed. Please try again.";
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Invalid payment status or booking ID.";
}
?>
