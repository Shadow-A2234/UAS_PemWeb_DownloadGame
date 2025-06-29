<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../config.php';

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $video = $_POST['video_path'];
    $image = $_POST['image_path'];
    $download = $_POST['download_link'];
    $is_available = isset($_POST['coming_soon']) ? 0 : 1;

    $stmt = $conn->prepare("UPDATE games SET title=?, description=?, video_path=?, image_path=?, download_link=?, is_available=? WHERE id=?");
    $stmt->bind_param("ssssssi", $title, $description, $video, $image, $download, $is_available, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: list_game.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Edit Game</title>
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
        background: #00bfff;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
    }
    .btn:hover {
        background: #0099cc;
    }
  </style>
</head>
<body>

<h1>Edit Game</h1>

<form method="POST">
  <label>Judul Game:</label>
  <input type="text" name="title" value="<?= htmlspecialchars($game['title']) ?>" required>

  <label>Deskripsi:</label>
  <textarea name="description" rows="4" required><?= htmlspecialchars($game['description']) ?></textarea>

  <label>Video Path (mp4):</label>
  <input type="text" name="video_path" value="<?= htmlspecialchars($game['video_path']) ?>">

  <label>Image Path:</label>
  <input type="text" name="image_path" value="<?= htmlspecialchars($game['image_path']) ?>">

  <label>Link Download:</label>
  <input type="text" name="download_link" value="<?= htmlspecialchars($game['download_link']) ?>">

  <label>
    <input type="checkbox" name="coming_soon" value="1" <?= $game['is_available'] ? '' : 'checked' ?>>
    Tandai sebagai Coming Soon
  </label>

  <br><br>
  <button type="submit" class="btn">Simpan Perubahan</button>
  <a href="list_game.php" class="btn">Kembali</a>
</form>

</body>
</html>
