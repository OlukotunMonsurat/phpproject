<?php
if (file_exists("messages.txt")) {
    $lines = file("messages.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $data = json_decode($line, true);
        if ($data) {
            $username = htmlspecialchars($data['username']);
           $message = htmlspecialchars($data['message']);

            $message = preg_replace('~(https?://[^\s]+)~', '<a href="$1" target="_blank">$1</a>', $message);
            $time = htmlspecialchars($data['time']);
            $imagePath = "profile_pics/" . $username . ".jpg";
            if (!file_exists($imagePath)) {
                $imagePath = "profile_pics/default.jpg";
            }
            echo '<div class="chat-message">
                    <div class="chat-header">
                      <img src="' . $imagePath . '" alt="Profile" class="chat-avatar">
                      <span class="chat-name">' . $username . '</span>
                    </div>
                    <div class="chat-text">' . $message . '</div>
                    <div class="chat-time">' . $time . '</div>
                  </div>';
        }
    }
}
?>
