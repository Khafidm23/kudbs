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

    try {
        if (mysqli_query($conn, $sql)) {
            header("Location: $redirect");
            exit();
        } else {
            throw new Exception();
        }
    } catch (Exception $e) {
        $_SESSION['message'] = ["type" => "error", "text" => "Data tidak bisa dihapus karena terdapat <b>Angsuran</b> dengan nama tersebut dalam table <b>Angsuran</b>. " ];
    }

}

mysqli_close($conn);
?>

<?php include '../_header.php'; ?>


                    <?php
                    if (isset($_SESSION['message'])) {
                        $messageType = $_SESSION['message']['type'];
                        $messageText = $_SESSION['message']['text'];
                        $alertClass = ($messageType == 'error') ? 'alert-danger' : 'alert-success';
                        echo "<div class='alert $alertClass' role='alert'>$messageText </div>";
                        unset($_SESSION['message']);
                    }
                    ?>
                    <a href="<?php echo htmlspecialchars($table); ?>.php" class="btn btn-primary">Kembali</a>

<?php include '../_footer.php'; ?>