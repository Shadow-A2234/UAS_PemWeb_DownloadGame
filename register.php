<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username sudah digunakan.";
    } else {
        $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insert->bind_param("ss", $username, $password);
        if ($insert->execute()) {
            $success = "Berhasil mendaftar! Silakan <a href='login.php' class='text-red-400'>login</a>.";
        } else {
            $error = "Gagal mendaftar.";
        }
        $insert->close();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Pixel Game Hub</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/register.css" />
</head>
<body>

<!-- Overlay transisi -->
<div id="transition-overlay"></div>

<!-- Audio -->
<audio id="click-sound" src="assets/audio/click-retro.mp3"></audio>
<audio id="bg-music" src="assets/audio/pixel anime relax.mp3" autoplay loop></audio>

<!-- Navbar -->
<nav class="navbar">
  <div class="logo">
    <a href="index.html" class="pixel-font">Shadow Pixel Game</a>
  </div>
  <ul class="menu">
    <li><a href="index.html" class="menu-link pixel-font">Home</a></li>
    <li><a href="login.php" class="menu-link pixel-font">Login</a></li>
    <li><button id="toggle-audio" class="menu-link pixel-font">Mute</button></li>
  </ul>
</nav>

<!-- Konten -->
<div class="container">
  <video id="bg-video" autoplay loop muted>
    <source src="assets/video/game6.mp4" type="video/mp4">
  </video>

  <div class="register-container pixel-font fade-in">
    <h2 class="mb-4">Register</h2>
    <?php if ($error): ?>
      <p class="error"><?= $error; ?></p>
    <?php elseif ($success): ?>
      <p class="success"><?= $success; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Register</button>
    </form>
  </div>
</div>

<script>
const overlay = document.getElementById('transition-overlay');
const clickSound = document.getElementById('click-sound');
const bgMusic = document.getElementById('bg-music');
const toggleAudio = document.getElementById('toggle-audio');

window.addEventListener('DOMContentLoaded', () => {
  overlay.classList.add('fade-in');
  setTimeout(() => overlay.classList.remove('fade-in'), 700);
});

document.querySelectorAll('a[href]').forEach(link => {
  link.addEventListener('click', function(e) {
    const href = this.getAttribute('href');
    if (href && !href.startsWith('#') && !href.startsWith('javascript')) {
      e.preventDefault();
      clickSound.play();
      overlay.classList.add('fade-out');
      setTimeout(() => {
        window.location.href = href;
      }, 500);
    }
  });
});

toggleAudio.addEventListener('click', () => {
  if (bgMusic.paused) {
    bgMusic.play();
    toggleAudio.textContent = 'Mute';
  } else {
    bgMusic.pause();
    toggleAudio.textContent = 'Unmute';
  }
});
</script>

</body>
</html>
