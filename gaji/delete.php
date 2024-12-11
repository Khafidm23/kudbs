<?php
include '../config/config.php';

$id = $_GET['id']; // Mendapatkan ID dari URL
$table = $_GET['table']; // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['rekap_gaji', 'helper']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    die("Tabel tidak valid.");
}

$sql = "DELETE FROM $table WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();

