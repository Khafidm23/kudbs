<?php
include '../config/config.php';

if (isset($_POST['del_all'], $_POST['table'], $_POST['redirect'])) {
    $ids = $_POST['del_all'];
    $table = $_POST['table'];
    $redirect = $_POST['redirect'];

    // Validasi nama tabel
    $allowed_tables = ['tb_hmbs01', 'tb_hmbs02', 'tb_hmbs03', 'tb_hmbs04', 'tb_hmbs05'];
    if (!in_array($table, $allowed_tables)) {
        header("Location: $redirect?status=error&message=Tabel tidak valid.");
        exit();
    }

    // Validasi dan filter UUID
    function isValidUUID($uuid) {
        return preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $uuid);
    }

    if (!is_array($ids)) {
        header("Location: $redirect?status=error&message=Input tidak valid.");
        exit();
    }

    $ids = array_filter($ids, 'isValidUUID');
    if (empty($ids)) {
        header("Location: $redirect?status=error&message=Tidak ada ID yang valid untuk dihapus.");
        exit();
    }

    // Buat placeholder untuk prepared statement
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = "DELETE FROM $table WHERE id IN ($placeholders)";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameter UUID ke statement
    mysqli_stmt_bind_param($stmt, str_repeat('s', count($ids)), ...$ids);

    try {
        if (mysqli_stmt_execute($stmt)) {
            header("Location: $redirect?status=success&message=Data berhasil dihapus.");
        } else {
            throw new Exception(mysqli_error($conn));
        }
    } catch (Exception $e) {
        $error_message = urlencode("Data tidak bisa dihapus karena terdapat Angsuran dengan nama tersebut dalam tabel Angsuran.");
        header("Location: $redirect?status=error&message=$error_message");
    } finally {
        if ($stmt) {
            mysqli_stmt_close($stmt);
        }
        exit();
    }
}

mysqli_close($conn);
?>


