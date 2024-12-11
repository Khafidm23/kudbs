<?php
include '../config/config.php';

$id = $_GET['id_user']; // Mendapatkan ID dari URL
$table = $_GET['table']; // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['tb_user']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    die("Tabel tidak valid.");
}

$sql = "DELETE FROM $table WHERE id_user=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id); // Menggunakan "s" untuk string karena UUID adalah string

if ($stmt->execute()) {
    header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect ke halaman sebelumnya
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
