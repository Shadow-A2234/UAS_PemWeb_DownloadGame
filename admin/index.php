<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../config.php';

// Ambil semua game
$result = $conn->query("SELECT * FROM games ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Panel - Game List</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Press Start 2P', cursive;
            padding: 2rem;
        }
        h1 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        th, td {
            padding: 0.8rem;
            border: 1px solid #444;
            text-align: left;
        }
        a.btn {
            background: #ff4d4d;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 0.5rem;
            display: inline-block;
        }
        a.btn:hover {
            background: #e60000;
        }
        .status {
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .available {
            background: #00ff88;
            color: black;
        }
        .coming {
            background: orange;
            color: black;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h1>Admin Panel - Daftar Game</h1>
    <a href="tambah.php" class="btn">+ Tambah Game</a>
</div>

<table>
    <thead>
        <tr>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td>
                <span class="status <?= $row['is_available'] ? 'available' : 'coming' ?>">
                    <?= $row['is_available'] ? 'Tersedia' : 'Akan Datang' ?>
                </span>
            </td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                <a href="hapus.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Yakin hapus game ini?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<a href="../dashboard.php" class="btn">← Kembali ke Dashboard</a>

</body>
</html>
