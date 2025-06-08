<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $username = $_SESSION['username'] ?? 'Anonymous';
        $time = date("H:i");
        $entry = json_encode([
            'username' => $username,
            'message' => $message,
            'time' => $time
        ]);
        file_put_contents("messages.txt", $entry . PHP_EOL, FILE_APPEND);
    }
  

    exit;
}

