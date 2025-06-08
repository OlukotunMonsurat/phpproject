<?php
session_start();
$usersFile = "users.json";
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

$darkMode = false;

if (isset($_GET['darkmode'])) {
    
    $darkMode = $_GET['darkmode'] === '1';
    setcookie('darkmode', $darkMode ? '1' : '0', time() + (86400 * 30), "/"); // Save for 30 days
    header("Location: ".$_SERVER['PHP_SELF']); // Redirect to remove query param
    exit;
} elseif (isset($_COOKIE['darkmode'])) {
    $darkMode = $_COOKIE['darkmode'] === '1';
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  if (!isset($users[$username])) {
    $users[$username] = $password;
    file_put_contents($usersFile, json_encode($users));
    $_SESSION['username'] = $username;
    header("Location: index.php");
    exit;
  } else {
    $error = "Username already exists!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/stylee.css">
</head>
<a href="?darkmode=<?php echo $darkMode ? '0' : '1'; ?>" style="
  position: fixed;
  top: 10px;
  right: 10px;
  background-color: #0066cc;
  color: white;
  padding: 8px 14px;
  border-radius: 20px;
  text-decoration: none;
  font-size: 14px;
  z-index: 999;
">
  <?php echo $darkMode ? '☀️ Light Mode' : '🌙 Dark Mode'; ?>
</a>
<body class="<?php echo $darkMode ? 'dark-mode' : ''; ?>">


<a href="?darkmode=<?php echo $darkMode ? '0' : '1'; ?>" class="dark-toggle">
  <?php echo $darkMode ? '☀️ Light Mode' : '🌙 Dark Mode'; ?>
</a>
    <div class="form-box register">
       
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
          <div class="box">
           <div class="login">
              <div class="loginBx">
                    <h2>
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Sign Up
                    <i class="fa-solid fa-heart"></i>
                    </h2>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" value="Sign in" />
                <div class="group">
                <a href="#">Already Sign up?</a>
                 <a href="login.php">Login</a></p>
            </div>
        </div>
               
        </form>
    <div>
</body>
</html>
