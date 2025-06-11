<?php
session_start();

if (!isset($_SESSION['username']) && !isset($_POST['show'])) {
    header("Location: login.php");
    exit;
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
.audio-floating {
  position: fixed;
  bottom: 20px;
  left: 20px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 8px;
}

#audio-toggle {
  background: #0077aa;
  color: white;
  border: none;
  border-radius: 50%;
  width: 45px;
  height: 45px;
  font-size: 20px;
  cursor: pointer;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  transition: background 0.3s ease;
}

#audio-toggle:hover {
  background: #005577;
}

#audio-toggle:focus {
  outline: none;
}

#audio-toggle:active {
  transform: scale(0.95);
}

#audio-toggle.hidden {
  display: none;
}

.audio-floating audio {
  display: none;
  width: 250px;
  max-width: 90vw;
  border-radius: 10px;
}

.audio-floating audio.show {
  display: block;
}


.game-container {
  margin: 20px auto;
  padding: 20px;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 16px;
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
  max-width: 700px;
  color: #fff;
  text-shadow: 1px 1px 2px #000;
}
.game-container button {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: #003344;
  padding: 10px 15px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}
.game-container button:hover {
  background: rgba(255, 255, 255, 0.3);
  color: #000;
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
  word-wrap: break-word; 
  overflow-wrap: break-word; 
  white-space: pre-wrap; 
}
.chat-message {
  background-color: rgba(0, 0, 0, 0.6);
  padding: 10px;
  border-radius: 12px;
  margin-bottom: 12px;
  color: white;
  width: 100%;
  box-sizing: border-box; 
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
  text-align: left; 
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

<body >
<div class="audio-floating">
  <button id="audio-toggle" title="Toggle Audio">🔊</button>
  <audio id="myAudio" src="audio/love.mp3" controls></audio>
</div>



<script>
  const audioToggle = document.getElementById('audio-toggle');
  const audioPlayer = document.getElementById('myAudio');

  audioToggle.addEventListener('click', () => {
    audioPlayer.classList.toggle('show');
  });
</script>



<div class="fish-background">
  <div class="fish"><?php for ($i = 0; $i < 15; $i++): ?><div class="koiCoil"></div><?php endfor; ?></div>
  <div class="fish"><?php for ($i = 0; $i < 15; $i++): ?><div class="koiCoil"></div><?php endfor; ?></div>
  <div class="seaLevel"></div>
</div>
<?php


$game = $_POST['game'] ?? null;
$result = '';
$message = '';
$showGame = isset($_POST['play_game']) || in_array($game, ['rps', 'guess', 'dice', 'math', 'scramble']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['play_game'])) {
    switch ($_POST['game']) {
      case 'memory':
    $emojis = ['🍎','🐟','🌈','🚀','🎵','🍕'];
    shuffle($emojis);
    $_SESSION['memory_board'] = array_merge($emojis, $emojis);
    shuffle($_SESSION['memory_board']);
    $_SESSION['memory_flipped'] = [];
    $result = "Start flipping! Memory game coming soon!";
    break;

        case 'rps':
            $choices = ['rock', 'paper', 'scissors'];
            $user = $_POST['rps_choice'];
            $computer = $choices[rand(0, 2)];
            if ($user === $computer) {
                $result = "It's a tie! You both chose $user.";
            } elseif (
                ($user === 'rock' && $computer === 'scissors') ||
                ($user === 'scissors' && $computer === 'paper') ||
                ($user === 'paper' && $computer === 'rock')
            ) {
                $result = "You win! $user beats $computer.";
            } else {
                $result = "You lose! $computer beats $user.";
            }
            break;

        case 'guess':
            $guess = (int)$_POST['guess_input'];
            if (!isset($_SESSION['target'])) $_SESSION['target'] = rand(1, 20);
            $target = $_SESSION['target'];
            if ($guess === $target) {
                $result = "🎉 Correct! You guessed the number $target.";
                unset($_SESSION['target']);
            } elseif ($guess < $target) {
                $result = "Too low! Try again.";
            } else {
                $result = "Too high! Try again.";
            }
            break;

        case 'dice':
            $die1 = rand(1, 6);
            $die2 = rand(1, 6);
            $sum = $die1 + $die2;
            $result = "🎲 You rolled a $die1 and a $die2. Total: $sum";
            break;

        case 'math':
    $userAnswer = isset($_POST['math_answer']) ? (int)$_POST['math_answer'] : null;
    $correctAnswer = $_SESSION['math_answer'] ?? null;

    if ($userAnswer !== null && $correctAnswer !== null) {
        if ($userAnswer === $correctAnswer) {
            $result = "✅ Correct!";
        } else {
            $result = "❌ Wrong! Correct answer was $correctAnswer.";
        }
        unset($_SESSION['math_question'], $_SESSION['math_answer']);
    } else {
        $result = "⚠️ Please submit an answer.";
    }
    break;

        case 'scramble':
            $guess = strtolower(trim($_POST['scramble_guess']));
            $original = $_SESSION['scramble_word'] ?? '';
            if ($guess === $original) {
                $result = "🎉 Correct! The word was '$original'.";
            } else {
                $result = "❌ Nope! Try again.";
            }
            break;

            case 'speed':
    $userAnswer = (int)$_POST['speed_answer'];
    $correct = $_SESSION['speed_answer'];
    $timeTaken = time() - $_SESSION['speed_time'];
    if ($userAnswer === $correct) {
        $result = "✅ Correct in $timeTaken seconds!";
    } else {
        $result = "❌ Wrong. Correct was $correct.";
    }
    unset($_SESSION['speed_answer'], $_SESSION['speed_time']);
    break;

case 'typing':
    $userInput = trim($_POST['typed']);
    $original = $_SESSION['typing_sentence'];
    $elapsed = round(microtime(true) - $_SESSION['typing_start'], 2);
    if ($userInput === $original) {
        $result = "⌨️ Well done! You typed it in $elapsed seconds.";
    } else {
        $result = "❌ Try again! You had typos.";
    }
    unset($_SESSION['typing_sentence'], $_SESSION['typing_start']);
    break;

    }
}
?>

<div class="game-container">
 

  <h2>🎯 Choose a Game to Pass Time from Boredom</h2>
  <form method="post" style="display: flex; flex-wrap: wrap; gap: 10px;">
    <button name="game" value="rps">🪨📄✂️ Rock Paper Scissors</button>
    <button name="game" value="guess">🔢 Guess the Number</button>
    <button name="game" value="dice">🎲 Dice Roll</button>
    <button name="game" value="math">➗ Math Quiz</button>
    <button name="game" value="scramble">🔤 Word Scramble</button>
    <button name="game" value="memory">🧠 Emoji Memory</button>
    <button name="game" value="speed">🏁 Fast Math</button>
    <button name="game" value="typing">⌨️ Typing Speed</button>

  </form>
</div>

<?php if ($game && !$result): ?>
  
  
  <div class="game-container">

    <form method="post">
      <input type="hidden" name="game" value="<?= $game ?>">
      <?php if ($game === 'rps'): ?>
        <label><input type="radio" name="rps_choice" value="rock" required> Rock</label>
        <label><input type="radio" name="rps_choice" value="paper"> Paper</label>
        <label><input type="radio" name="rps_choice" value="scissors"> Scissors</label>
      <?php elseif ($game === 'guess'): ?>
        <label>Guess a number (1–20): <input type="number" name="guess_input" min="1" max="20" required></label>
      <?php elseif ($game === 'math'):
          $a = rand(1, 10); $b = rand(1, 10);
          $_SESSION['math_answer'] = $a + $b;
          echo "<label>What is $a + $b? <input type='number' name='math_answer' required></label>";
      ?>
      <?php elseif ($game === 'scramble'):
          $words = ['planet', 'banana', 'rocket', 'coding', 'winter', 'summer', 'wealth' , 'developer'];
          $word = $words[array_rand($words)];
          $_SESSION['scramble_word'] = $word;
          $scrambled = str_shuffle($word);
          echo "<label>Unscramble this word: <b>$scrambled</b></label><br><input type='text' name='scramble_guess' required>";
      ?>
      <?php elseif ($game === 'speed'): ?>
  <?php 
    
    if (!isset($_SESSION['speed_question']) || !isset($_SESSION['speed_answer'])) {
        $a = rand(10, 99);
        $b = rand(10, 99);
        $_SESSION['speed_answer'] = $a + $b;
        $_SESSION['speed_question'] = "What is $a + $b?";
        $_SESSION['speed_time'] = time();
    }
  ?>
  <label><?= $_SESSION['speed_question'] ?></label><br>
  <label>Answer: <input type="number" name="speed_answer" required></label>
<?php elseif ($game === 'typing'): ?>
  <?php 
    if (!isset($_SESSION['typing_sentence'])) {
      $sentences = [
        "The quick brown fox jumps over the lazy dog",
        "PHP is fun to learn and powerful",
        "Sea breeze feels nice on a sunny day",
        "Website developed by wealthDev",
        "PHP is a sever side language",
        "Make hey while the sun shine"
        
      ];
      $sentence = $sentences[array_rand($sentences)];
      $_SESSION['typing_sentence'] = $sentence;
      $_SESSION['typing_start'] = microtime(true);
    }
  ?>
  <label>Type this sentence:</label><br>
  <b><?= htmlspecialchars($_SESSION['typing_sentence']) ?></b><br>
  <label>Retype: <input type="text" name="typed" required style="width: 90%;"></label>


      <?php endif; ?>
      <br><button type="submit" name="play_game">Play</button>
    </form>
  </div>
<?php endif; ?>

<?php if ($result): ?>
 <div class="game-container">

    <strong>Result:</strong> <?= $result ?>
  </div>
<?php endif; ?>

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
    <?php if (!empty($uploadSuccess)) echo "<p style='color:green;'></p>"; ?>
    <?php if (!empty($uploadError)) echo "<p style='color:red;'>$uploadError</p>"; ?>


   <?php if ($showContent && count($images) > 0): ?>
  <div class="slide-container">
    <img src="<?= htmlspecialchars($images[$currentSlide]) ?>" alt="Slide Image" class="slide-image" />
    
    <?php if (!empty($descriptions[$currentSlide])): ?>
      <div class="image-description">
        <p><?= htmlspecialchars($descriptions[$currentSlide]) ?></p>
      </div>
    <?php endif; ?>

    <form method="POST">
      <input type="hidden" name="slide" value="<?= $nextSlide ?>">
      <input type="hidden" name="show" value="1">
      <button type="submit">➡️ Next</button>
    </form>
  </div>
<?php endif; ?>

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