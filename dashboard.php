<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php'; // pastikan database tersedia

// Ambil role user dari session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; // Default role jika tidak ada
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Shadow Pixel Game</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/dashboard.css" />
  <style>
    body::-webkit-scrollbar { display: none; }
    .pixel-font { font-family: 'Press Start 2P', cursive; }
    .container { position: relative; z-index: 2; }
    #bg-video {
      position: fixed; top: 0; left: 0;
      min-width: 100%; min-height: 100%;
      width: auto; height: auto;
      object-fit: cover;
      z-index: -1;
      filter: blur(2px);
    }
    #transition-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background: black;
      z-index: 9999;
      opacity: 0; pointer-events: none;
      transition: opacity 0.5s ease-in-out;
    }
    #transition-overlay.fade-in { animation: zoomFadeIn 0.6s forwards; }
    #transition-overlay.fade-out { animation: slideFadeOut 0.5s forwards; }
    @keyframes zoomFadeIn {
      0% { opacity: 1; transform: scale(1.2); }
      100% { opacity: 0; transform: scale(1); }
    }
    @keyframes slideFadeOut {
      0% { opacity: 0; transform: translateY(0); }
      100% { opacity: 1; transform: translateY(-50px); }
    }
    .game-card-hover {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      transform: scale(1);
    }
    .game-card-hover:hover {
      transform: scale(1.05) translateY(-6px);
      box-shadow: 0 12px 24px rgba(255, 77, 77, 0.3);
      z-index: 5;
    }
  </style>
</head>
<body class="pixel-font text-white bg-black">

<video id="bg-video" autoplay loop muted>
  <source src="assets/video/game7.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>

<div id="transition-overlay"></div>
<audio id="click-sound" src="assets/audio/click-retro.mp3"></audio>
<audio id="bg-music" src="assets/audio/Lost Within The End - Slowed & Reverb.mp3" autoplay loop></audio>

<nav class="navbar flex justify-between items-center px-6 py-4 fixed top-0 w-full bg-black bg-opacity-20 z-50">
  <div class="text-xl">Shadow Pixel Game</div>
  <div class="text-sm space-x-4">
    <?php if ($role !== 'admin'): ?>
      <a href="index.html" class="text-green-400 hover:text-green-600">Home</a> |
    <?php endif; ?>
    Welcome, <?= htmlspecialchars($_SESSION['username']) ?> |
    <a href="logout.php" class="text-red-400 hover:text-red-600">Logout</a>
  </div>
</nav>

<div class="container mt-24 px-6">
  <h2 class="text-center text-2xl mb-6">Download Game</h2>

  <?php if ($role === 'admin'): ?>
    <div class="text-right mb-4">
      <a href="admin/tambah.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
        ➕ Tambah Game
      </a>
      <a href="admin/list_game.php" class="bg-yellow-400 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded">
        🛠 Kelola Game
      </a>
    </div>
  <?php endif; ?>

  <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6">
    <?php
    $result = mysqli_query($conn, "SELECT * FROM games WHERE is_available = 0 ORDER BY id DESC");
    while ($row = mysqli_fetch_assoc($result)):
    ?>
    <div class="bg-gray-800 bg-opacity-80 p-4 rounded-xl shadow-lg text-center game-card-hover">
      <img src="<?= htmlspecialchars($row['image_path']) ?>" class="w-full rounded mb-2" alt="<?= htmlspecialchars($row['title']) ?>">
      <h3 class="text-lg mb-2"><?= htmlspecialchars($row['title']) ?></h3>
      <?php if ($row['is_available']): ?>
  <a href="<?= htmlspecialchars($row['download_link']) ?>" class="btn" target="_blank">Download</a>
<?php else: ?>
  <span class="inline-block bg-yellow-500 text-black font-bold px-4 py-2 rounded">Coming Soon</span>
<?php endif; ?>
    </div>
    <?php endwhile; ?>
  </div>
</div>

<button id="toggle-audio" class="fixed bottom-4 right-4 px-4 py-2 bg-red-500 text-white rounded pixel-font text-sm">Mute</button>

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
