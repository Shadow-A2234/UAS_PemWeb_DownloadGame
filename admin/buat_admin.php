<?php
require_once '../config.php'; // ← diperbaiki

// Cek dulu apakah user 'admin' sudah ada
$username = 'admin';
$check = $conn->prepare("SELECT * FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "User admin sudah ada. Silakan login dengan username: admin dan password: admin123";
} else {
    // Buat user admin baru
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $role = 'admin';

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        echo "✅ Admin berhasil dibuat! Username: admin | Password: admin123";
    } else {
        echo "❌ Gagal menambahkan admin: " . $stmt->error;
    }
    $stmt->close();
}
?>
