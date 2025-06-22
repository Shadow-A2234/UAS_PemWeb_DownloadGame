<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$error = '';
$success = '';

// Ambil data game berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Game tidak ditemukan.");
}

$game = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $video = $_POST['video_path'];
    $image = $_POST['image_path'];
    $download = $_POST['download_link'];
    $available = isset($_POST['is_available']) ? 1 : 0;

    $update = $conn->prepare("UPDATE games SET title=?, description=?, video_path=?, image_path=?, download_link=?, is_available=? WHERE id=?");
    $update->bind_param("ssssssi", $title, $description, $video, $image, $download, $available, $id);

    if ($update->execute()) {
        $success = "Game berhasil diperbarui.";
        $game = array_merge($game, $_POST); // update tampilan
    } else {
        $error = "Gagal memperbarui data.";
    }
    $update->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Game - Admin</title>
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

<h1>Edit Game</h1>

<?php if ($success): ?>
  <p class="success"><?= $success ?></p>
<?php elseif ($error): ?>
  <p class="error"><?= $error ?></p>
<?php endif; ?>

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
    <input type="checkbox" name="is_available" <?= $game['is_available'] ? 'checked' : '' ?>>
    Tersedia untuk diunduh
  </label><br>

  <button type="submit" class="btn">Simpan Perubahan</button>
  <a href="index.php" class="btn">Kembali</a>
</form>

</body>
</html>
