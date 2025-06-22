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

// Hapus data game
$stmt = $conn->prepare("DELETE FROM games WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?status=deleted");
    exit();
} else {
    echo "Gagal menghapus game.";
}
$stmt->close();
?>
