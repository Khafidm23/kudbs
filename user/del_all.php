<?php
ob_start(); // Memulai output buffering

include '../_header.php';

include '../config/config.php';

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Query untuk menghapus semua data log
    $delete_query = "DELETE FROM log";

    // Eksekusi query
    if (mysqli_query($conn, $delete_query)) {
        // Jika berhasil, arahkan kembali ke halaman log dengan pesan sukses
        header("Location: log.php?message=All records have been successfully deleted");
        exit;
    } else {
        // Jika gagal, arahkan kembali ke halaman log dengan pesan error
        header("Location: log.php?error=Failed to delete records: " . mysqli_error($conn));
        exit;
    }
}

// Tutup koneksi database
mysqli_close($conn);

ob_end_flush(); // Mengakhiri output buffering dan mengirimkan output ke browser
?>
