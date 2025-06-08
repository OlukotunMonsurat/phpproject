<?php
session_start();

if (!isset($_SESSION['username']) && !isset($_POST['show'])) {
    header("Location: login.php");
    exit;
}

$darkMode = false;
if (isset($_GET['darkmode'])) {
    $darkMode = $_GET['darkmode'] === '1';
    setcookie('darkmode', $darkMode ? '1' : '0', time() + (86400 * 30), "/");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
} elseif (isset($_COOKIE['darkmode'])) {
    $darkMode = $_COOKIE['darkmode'] === '1';
}

$showContent = isset($_POST['show']);

$descriptions = [
   
];

$images = glob("images/*.jpg");
$currentSlide = isset($_POST['slide']) ? (int)$_POST['slide'] : 0;
$nextSlide = ($currentSlide + 1) % count($images);

if (isset($_POST['upload']) && isset($_FILES['profile_pic'])) {
    $targetDir = "profile_pics/";
    $username = $_SESSION['username'];
    $targetFile = $targetDir . basename($username . ".jpg");

    $imageFileType = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);

    if ($check !== false && in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile);
        $uploadSuccess = true;
    } else {
        $uploadError = "Invalid image file. Please upload a JPG, PNG, or GIF.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Group Love</title>
<link rel="stylesheet" href="style.css">
<style>
body {
  font-family: Arial, sans-serif;
  background: rgb(1, 188, 250);
  margin: 0;
  padding: 20px;
  text-align: center;
}
.slide-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 20px auto;
  max-width: 360px;
  background: rgba(255, 255, 255, 0.15);
  padding: 20px;
  border-radius: 16px;
  backdrop-filter: blur(12px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.slide-image {
  width: 100%;
  max-width: 320px;
  height: auto;
  border-radius: 12px;
  display: block;
  object-fit: cover;
}

.image-description {
  margin-top: 12px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(6px);
  border-radius: 10px;
  padding: 12px;
  color: #000;
  font-size: 14px;
  width: 100%;
  text-align: left;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.image-description p {
  margin: 5px 0;
  line-height: 1.5;
}


img {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
  display: block;
  margin: 10px auto;
}


@media (min-width: 768px) {
  img {
    max-width: 300px; 
  }
}

.chat-box {
  width: 95%;
  max-width: 500px;
  margin: 15px auto;
  background: rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(8px);
  padding: 8px;
  border-radius: 8px;
  overflow-y: auto;
  max-height: 220px;
  box-sizing: border-box; 
}
.chat-text {
  font-size: 14px;
  line-height: 1.4;
  margin-top: 4px;
  word-wrap: break-word; /* breaks long words */
  overflow-wrap: break-word; /* ensures compatibility */
  white-space: pre-wrap; /* preserves line breaks but wraps long text */
}
.chat-message {
  background-color: rgba(0, 0, 0, 0.6);
  padding: 10px;
  border-radius: 12px;
  margin-bottom: 12px;
  color: white;
  width: 100%;
  box-sizing: border-box; /* <-- Ensure padding stays within bounds */
  display: flex;
  flex-direction: column;
  overflow-wrap: break-word;
  word-wrap: break-word;
}



.chat-header {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 2px;
}
.chat-avatar {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  object-fit: cover;
  border: 1px solid #ccc;
}
.chat-name {
  font-weight: bold;
  font-size: 15px;
}
.chat-message,
.chat-text {
  text-align: left; /* force left alignment */
}

.chat-text {
  font-size: 14px;
  line-height: 1.4;
  margin-top: 4px;
  overflow-wrap: break-word;
  word-break: break-word; 
}
.chat-time {
  font-size: 12px;
  color: #ccc;
  margin-top: 4px;
}
input[type=text], input[type=submit], button {
  padding: 6px 10px;
  font-size: 13px;
  margin-top: 4px;
}
input[type=text] {
  width: 65%;
}
.logout {
  margin-top: 15px;
  color: crimson;
}
.speech-button {
  background: rgba(255, 255, 255, 0.1);
  color: #004466;
  border: 2px solid #004466;
  padding: 12px 20px;
  font-size: 14px;
  border-radius: 20px;
  cursor: pointer;
  font-family: 'Comic Sans MS', cursive;
  max-width: 300px;
  white-space: normal;
  width: 90%;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
  position: relative;
}
.speech-button:hover {
  background: rgba(255, 255, 255, 0.2);
  color: #002233;
 
}
.speech-button::after {
  content: "";
  position: absolute;
  bottom: -18px;
  left: 24px;
  border: 8px solid transparent;
  border-top-color: rgba(255, 255, 255, 0.1);
}
.speech-button span {
  display: inline-block;
  animation: talkBounce 1.5s ease-in-out infinite;
}
@keyframes talkBounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}
@media (max-width: 600px) {
  .chat-name { font-size: 14px; }
  .chat-text { font-size: 13px; }
  .chat-message { padding: 8px; }
}
footer {
  text-align: center;
  padding: 15px;
  background: rgba(0, 0, 0, 0.05);
  color: #444;
  font-weight: bold;
  border-top: 1px solid #ccc;
  margin-top: 40px;
}

</style>
</head>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form[action="chat.php"]');
  const input = form.querySelector('input[name="message"]');
  const chatBox = document.getElementById('chat');

  form.addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent page reload

    const message = input.value.trim();
    if (!message) return;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'chat.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
      if (xhr.status === 200) {
        input.value = '';
        // Refresh chat box
        fetch('messages.php')  // A new PHP file to just return chat HTML
          .then(res => res.text())
          .then(html => {
            chatBox.innerHTML = html;
          });
      }
    };

    xhr.send('message=' + encodeURIComponent(message));
  });
});
</script>

<body>
<div class="fish-background">
  <div class="fish"><?php for ($i = 0; $i < 15; $i++): ?><div class="koiCoil"></div><?php endfor; ?></div>
  <div class="fish"><?php for ($i = 0; $i < 15; $i++): ?><div class="koiCoil"></div><?php endfor; ?></div>
  <div class="seaLevel"></div>
</div>

<?php if (!$showContent): ?>
<form method="post">
  <button type="submit" name="show" value="1" class="speech-button">
    <span>Welcome to my sea 🌊, click me to see my members🐟 and have a conversation❤️💕</span>
  </button>
</form>
<?php else: ?>
  <div>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <h3>look down⬇️🐟❤️</h3>

    <!-- 🆕 Profile Picture Upload -->
    <h4>Upload Your Profile Picture To Start Chat With Other Fishes🐟🐠</h4>
    <form method="post" enctype="multipart/form-data">
      <input type="file" name="profile_pic" accept="image/*" required onchange="previewImage(event)" />
      <button type="submit" name="upload">Upload</button>
      <input type="hidden" name="show" value="1">
      <img id="preview" />
    </form>
    <?php if (!empty($uploadSuccess)) echo "<p style='color:green;'>uploaded!</p>"; ?>
    <?php if (!empty($uploadError)) echo "<p style='color:red;'>$uploadError</p>"; ?>


    <form method="post">
      <input type="hidden" name="show" value="1" />
      <input type="hidden" name="slide" value="<?= $nextSlide ?>" />
    <div class="slide-container">
  <img src="<?= $images[$currentSlide] ?>" alt="Slide" class="slide-image" />
  <?php
    $filename = basename($images[$currentSlide]);
    if (isset($descriptions[$filename])) {
        echo "<div class='image-description'>";
        foreach ($descriptions[$filename] as $key => $value) {
            echo "<p><strong>$key:</strong> $value</p>";
        }
        echo "</div>";
    }
  ?>
</div>

      <br />
      <input type="submit" value="Next Image" />
    </form>
  </div>

  <div class="chat-box" id="chat">
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
                    $imagePath = "profile_pics/group member dev.jpg";
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
  </div>
  <form method="post" action="chat.php">
    <input type="text" name="message" placeholder="Type a message..." required />
    <button type="submit">Send</button>
  </form>
  <form method="post" class="logout" action="logout.php">
    <button type="submit">Logout</button>
  </form>
<?php endif; ?>
<script>
function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function(e) {
    const preview = document.getElementById('preview');
    preview.src = e.target.result;
    preview.style.display = 'block';
  };
  reader.readAsDataURL(event.target.files[0]);
}
</script>

<footer>&copy; <?= date("Y") ?> wealthDEV — All rights reserved</footer>


</body>
</html>
