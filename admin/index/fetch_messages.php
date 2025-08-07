<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_email = $_POST['receiver_email'];

    $sql = "SELECT * FROM messages WHERE receiver_email = ? ORDER BY timestamp ASC";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $receiver_email);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode($messages);

    $stmt->close();
    $con->close();
}
?>
