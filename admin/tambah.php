<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../config.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $video = $_POST['video_path'];
    $image = $_POST['image_path'];
    $download = $_POST['download_link'];

    $stmt = $conn->prepare("INSERT INTO games (title, description, video_path, image_path, download_link, is_available) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssss", $title, $description, $video, $image, $download);

    if ($stmt->execute()) {
        $success = "Game berhasil ditambahkan!";
    } else {
        $error = "Gagal menambahkan game.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Tambah Game - Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet" />
  <style>
    body {
        background-color: black;
        color: white;
        font-family: 'Press Start 2P', cursive;
        padding: 2rem;
    }
    input, textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 1rem;
        background: #222;
        border: 2px solid #ff4d4d;
        color: white;
        border-radius: 4px;
    }
    .btn {
        background: #ff4d4d;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-top: 1rem;
    }
    .btn:hover {
        background: #e60000;
    }
    .success { color: #00ff88; margin-bottom: 1rem; }
    .error { color: #ff4d4d; margin-bottom: 1rem; }
  </style>
</head>
<body>

<h1>Tambah Game Baru</h1>

<?php if ($success): ?>
  <p class="success"><?= $success ?></p>
<?php elseif ($error): ?>
  <p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
  <label>Judul Game:</label>
  <input type="text" name="title" required>

  <label>Deskripsi:</label>
  <textarea name="description" rows="4" required></textarea>

  <label>Video Path (mp4):</label>
  <input type="text" name="video_path" placeholder="contoh: assets/video/game1.mp4">

  <label>Image Path:</label>
  <input type="text" name="image_path" placeholder="contoh: assets/img/game1.jpg">

  <label>Link Download:</label>
  <input type="text" name="download_link" placeholder="contoh: https://example.com/file.zip">

  <button type="submit" class="btn">Simpan</button>
  <a href="index.php" class="btn">Kembali</a>
</form>

</body>
</html>
