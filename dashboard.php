<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
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
    body::-webkit-scrollbar {
      display: none;
    }
    .pixel-font {
      font-family: 'Press Start 2P', cursive;
    }
    .container {
      position: relative;
      z-index: 2;
    }
    #bg-video {
      position: fixed;
      top: 0;
      left: 0;
      min-width: 100%;
      min-height: 100%;
      width: auto;
      height: auto;
      object-fit: cover;
      z-index: -1;
      filter: blur(2px);
    }
    #transition-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: black;
      z-index: 9999;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.5s ease-in-out;
    }
    #transition-overlay.fade-in {
      animation: zoomFadeIn 0.6s forwards;
    }
    #transition-overlay.fade-out {
      animation: slideFadeOut 0.5s forwards;
    }
    @keyframes zoomFadeIn {
      0% { opacity: 1; transform: scale(1.2); }
      100% { opacity: 0; transform: scale(1); }
    }
    @keyframes slideFadeOut {
      0% { opacity: 0; transform: translateY(0); }
      100% { opacity: 1; transform: translateY(-50px); }
    }

    /* Efek hover timbul */
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

<!-- Video Background -->
<video id="bg-video" autoplay loop muted>
  <source src="assets/video/game7.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>

<!-- Overlay & Audio -->
<div id="transition-overlay"></div>
<audio id="click-sound" src="assets/audio/click-retro.mp3"></audio>
<audio id="bg-music" src="assets/audio/Lost Within The End - Slowed & Reverb.mp3" autoplay loop></audio>

<!-- Navbar -->
<nav class="navbar flex justify-between items-center px-6 py-4 fixed top-0 w-full bg-black bg-opacity-20 z-50">
  <div class="text-xl">Shadow Pixel Game</div>
  <div class="text-sm">
    Welcome, <?= htmlspecialchars($_SESSION['username']) ?> |
    <a href="logout.php" class="text-red-400 hover:text-red-600">Logout</a>
  </div>
</nav>

<!-- Konten Game -->
<div class="container mt-24 px-6">
  <h2 class="text-center text-2xl mb-6">Download Game</h2>
  <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6">

    <div class="bg-gray-800 bg-opacity-80 p-4 rounded-xl shadow-lg text-center game-card-hover">
      <img src="assets/img/up1.png" class="w-full rounded mb-2" alt="Coming Soon">
      <h3 class="text-lg mb-2">Shadow of the Forgotten Dungeon</h3>
      <a href="#" class="btn opacity-60 cursor-not-allowed pointer-events-none">Coming Soon</a>
    </div>

    <div class="bg-gray-800 bg-opacity-80 p-4 rounded-xl shadow-lg text-center game-card-hover">
      <img src="assets/img/up2.png" class="w-full rounded mb-2" alt="Coming Soon">
      <h3 class="text-lg mb-2">Shadow Parallel World ARC</h3>
      <a href="#" class="btn opacity-60 cursor-not-allowed pointer-events-none">Coming Soon</a>
    </div>

    <div class="bg-gray-800 bg-opacity-80 p-4 rounded-xl shadow-lg text-center game-card-hover">
      <img src="assets/img/up3.png" class="w-full rounded mb-2" alt="Coming Soon">
      <h3 class="text-lg mb-2">Shadow Lost In CyberCity</h3>
      <a href="#" class="btn opacity-60 cursor-not-allowed pointer-events-none">Coming Soon</a>
    </div>

  </div>
</div>

<!-- Tombol Audio -->
<button id="toggle-audio" class="fixed bottom-4 right-4 px-4 py-2 bg-red-500 text-white rounded pixel-font text-sm">Mute</button>

<!-- Script -->
<script>
const overlay = document.getElementById('transition-overlay');
const clickSound = document.getElementById('click-sound');
const bgMusic = document.getElementById('bg-music');
const toggleAudio = document.getElementById('toggle-audio');

// Transisi saat masuk
window.addEventListener('DOMContentLoaded', () => {
  overlay.classList.add('fade-in');
  setTimeout(() => overlay.classList.remove('fade-in'), 700);
});

// Transisi keluar saat klik link
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

// Tombol mute
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
