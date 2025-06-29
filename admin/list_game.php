<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../config.php';

$result = mysqli_query($conn, "SELECT * FROM games ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Kelola Game</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <style>
    body {
      background: black;
      color: white;
      font-family: 'Press Start 2P', cursive;
      padding: 2rem;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2rem;
    }
    th, td {
      border: 1px solid #ff4d4d;
      padding: 0.5rem;
      text-align: center;
    }
    a.btn {
      padding: 6px 10px;
      margin: 0 2px;
      text-decoration: none;
      border-radius: 4px;
    }
    .edit-btn { background: #00bfff; color: white; }
    .delete-btn { background: #ff4d4d; color: white; }
  </style>
</head>
<body>
  <h2>Daftar Game</h2>
  <a href="tambah.php" class="btn edit-btn">➕ Tambah Game</a>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Judul</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= $row['is_available'] ? 'Tersedia' : 'Coming Soon' ?></td>
          <td>
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn edit-btn">Edit</a>
            <a href="hapus.php?id=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Yakin ingin menghapus game ini?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
