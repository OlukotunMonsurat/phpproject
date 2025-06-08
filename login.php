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
  $password = $_POST['password'];

  if (isset($users[$username]) && password_verify($password, $users[$username])) {
    $_SESSION['username'] = $username;
    header("Location: index.php");
    exit;
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title>
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

   
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
       <div class="box">
           <div class="login">
              <div class="loginBx">
                    <h2>
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                    <i class="fa-solid fa-heart"></i>
                    </h2>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" value="Sign in" />
                <div class="group">
                <a href="#">Don't have an acount?</a>
                <a href="register.php">Sign up</a>
            </div>
        </div>
    <form>
</body>
</html>                     