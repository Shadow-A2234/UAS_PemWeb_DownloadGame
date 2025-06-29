
<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../config.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $download = $_POST['download_link'];

    // Folder tujuan upload
    $image_dir = '../assets/img/';
    $video_dir = '../assets/video/';

    // Ambil nama file
    $image_name = basename($_FILES['image_file']['name']);
    $video_name = basename($_FILES['video_file']['name']);

    $image_path = $image_dir . $image_name;
    $video_path = $video_dir . $video_name;

    // Upload file
    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $image_path) &&
        move_uploaded_file($_FILES['video_file']['tmp_name'], $video_path)) {

        // Simpan ke database (path disesuaikan untuk dashboard)
        $image_db_path = 'assets/img/' . $image_name;
        $video_db_path = 'assets/video/' . $video_name;

        $is_available = isset($_POST['coming_soon']) ? 0 : 1;

        $stmt = $conn->prepare("INSERT INTO games (title, description, video_path, image_path, download_link, is_available) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $title, $description, $video_db_path, $image_db_path, $download, $is_available);

        if ($stmt->execute()) {
            $success = "Game berhasil ditambahkan!";
        } else {
            $error = "Gagal menyimpan ke database.";
        }
        $stmt->close();

    } else {
        $error = "Gagal mengupload file.";
    }
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

<form method="POST" enctype="multipart/form-data">

  <label>Judul Game:</label>
  <input type="text" name="title" required>

  <label>Deskripsi:</label>
  <textarea name="description" rows="4" required></textarea>

  <label>Pilih Video (mp4):</label>
  <input type="file" name="video_file" accept="video/mp4" required>

  <label>Pilih Gambar:</label>
  <input type="file" name="image_file" accept="image/*" required>

  <label>Link Download:</label>
  <input type="text" name="download_link" placeholder="contoh: https://example.com/file.zip">

  <label>
  <input type="checkbox" name="coming_soon" value="1">
  Tandai sebagai *Coming Soon*
  </label>


  <button type="submit" class="btn">Simpan</button>
  <a href="../dashboard.php" class="btn">Kembali</a>
</form>

</body>
</html>
