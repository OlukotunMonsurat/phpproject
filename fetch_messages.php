<?php
$messages = [];

if (file_exists("messages.txt")) {
    $lines = file("messages.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $data = json_decode($line, true);
        if ($data) {
            $data['username'] = htmlspecialchars($data['username']);
            $data['mesge'] = htmlspecialchars($data['mesge']);
            $data['time'] = htmlspecialchars($data['time']);
            $messages[] = $data;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($messages);
