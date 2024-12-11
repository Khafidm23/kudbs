<?php
include '../config/config.php';

$id = $_GET['id']; // Mendapatkan ID dari URL
$table = $_GET['table']; // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['tb_hmbs01', 'tb_hmbs02', 'tb_hmbs03', 'tb_hmbs04', 'tb_hmbs05']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    $redirect_url = $table . ".php?status=error&message=" . urlencode("Tabel tidak valid.");
    header("Location: $redirect_url");
    exit();
}

// Persiapkan query
$sql = "DELETE FROM $table WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);

try {
    if ($stmt->execute()) {
        $redirect_url = $table . ".php?status=success&message=" . urlencode("Data berhasil dihapus.");
        header("Location: $redirect_url");
        exit();
    } else {
        throw new Exception("Gagal menghapus data.");
    }
} catch (Exception $e) {
    $redirect_url = $table . ".php?status=error&message=" . urlencode("Data tidak bisa dihapus karena terdapat Angsuran dengan nama tersebut dalam tabel Angsuran.");
    header("Location: $redirect_url");
    exit();
} finally {
    if ($stmt) {
        $stmt->close();
    }
    if ($conn) {
        $conn->close();
    }
}
?>

