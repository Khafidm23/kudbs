<?php
include '../config/config.php';

if (isset($_POST['del_all']) && isset($_POST['table']) && isset($_POST['redirect'])) {
    $ids = $_POST['del_all'];
    $table = $_POST['table'];
    $redirect = $_POST['redirect'];
    // Mengapit setiap ID dengan tanda kutip tunggal
    $id_list = implode(',', array_map(function($id) {
        return "'" . $id . "'";
    }, $ids));

    $sql = "DELETE FROM $table WHERE id IN ($id_list)";
    if (mysqli_query($conn, $sql)) {
        echo "Data berhasil dihapus.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    
}

mysqli_close($conn);
header("Location: $redirect");
exit();
