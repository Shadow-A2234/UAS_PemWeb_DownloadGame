<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
require_once 'config.php';

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Ambil role dari database juga
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Disesuaikan dari database (admin/user)
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "User tidak ditemukan.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Pixel Game Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/login.css" />
    <style>
      body::-webkit-scrollbar {
          display: none;
      }
    </style>
</head>
<body>

<!-- Overlay transisi -->
<div id="transition-overlay"></div>

<!-- Audio: klik & musik -->
<audio id="click-sound" src="assets/audio/click-retro.mp3"></audio>
<audio id="bg-music" src="assets/audio/Pixel Animation with Chill Music.mp3" loop autoplay></audio>

<!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="index.html" class="text-2xl pixel-font">Shadow Pixel Game</a>
    </div>
    <div class="menu-toggle" id="mobile-menu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </div>
    <ul class="menu">
        <li><a href="index.html" class="menu-link pixel-font">Home</a></li>
        <li><a href="register.php" class="menu-link pixel-font">Register</a></li>
        <li><button id="toggle-audio" class="menu-link pixel-font">Mute</button></li>
    </ul>
</nav>

<!-- Background video -->
<div class="container">
    <video id="bg-video" autoplay loop muted>
        <source src="assets/video/game4.mp4" type="video/mp4" />
        Your browser does not support the video tag.
    </video>

    <div class="login-container pixel-font fade-in">
        <h2 class="text-xl mb-4">Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit" class="btn">Login</button>
        </form>
        <p class="mt-4 text-sm">Belum punya akun? <a href="register.php" class="text-red-400">Register</a></p>
    </div>
</div>

<script>
const overlay = document.getElementById('transition-overlay');
const clickSound = document.getElementById('click-sound');
const bgMusic = document.getElementById('bg-music');
const toggleAudio = document.getElementById('toggle-audio');

// Fade in saat halaman dibuka
window.addEventListener('DOMContentLoaded', () => {
    overlay.classList.add('fade-in');
    setTimeout(() => overlay.classList.remove('fade-in'), 700);
});

// Klik link = mainkan suara + transisi
document.querySelectorAll('a[href]').forEach(link => {
    link.addEventListener('click', function (e) {
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

// Tombol mute/unmute musik
if (toggleAudio) {
    toggleAudio.addEventListener('click', () => {
        if (bgMusic.paused) {
            bgMusic.play();
            toggleAudio.textContent = 'Mute';
        } else {
            bgMusic.pause();
            toggleAudio.textContent = 'Unmute';
        }
    });
}
</script>

</body>
</html>
