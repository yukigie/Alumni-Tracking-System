<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_email = $_POST['sender_email'];
    $receiver_email = $_POST['receiver_email'];
    $message = $_POST['message'];

    $stmt = $con->prepare("INSERT INTO messages (sender_email, receiver_email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $sender_email, $receiver_email, $message);

    if ($stmt->execute()) {
        echo "Message sent!";
    } else {
        echo "Error: " . $con->error;
    }

    $stmt->close();
    $con->close();
}
?>
