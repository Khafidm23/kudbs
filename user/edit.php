<?php
include '../_header.php'; // Menyertakan header

include '../config/config.php';

$id = $_GET['id_user']; // Mendapatkan ID dari URL
$notification = "";
$table = $_GET['table'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $newPassword = $_POST['password'];

    if (!empty($newPassword)) {
        $hashedPassword = md5($newPassword); // Hash password dengan md5
        $sql = "UPDATE $table SET username=?, password=? WHERE id_user=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user, $hashedPassword, $id);
    } else {
        // Jika password baru tidak diisi, hanya update username
        $sql = "UPDATE $table SET username=? WHERE id_user=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user, $id);
    }

    if ($stmt->execute()) {
        $notification = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
    } else {
        $error = $stmt->error;
        $notification = "<div class='alert alert-danger'>$error</div>";
    }
}

$sql = "SELECT * FROM $table WHERE id_user=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<div class="col-md-8 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            <b> Ubah Username & Password </b>
        </div>
        <div class="card-body">
            <form action="" method="POST" onsubmit="return validateForm()">
                <div id="data-container">
                    <div class="data-entry d-flex justify-content-center align-items-center">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username :</label>
                            <input type="text" class="form-control" name="username"
                                value="<?= htmlspecialchars($data['username']) ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Baru :</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="text-center">
                    <hr>
                    <?php echo $notification; ?>
                    <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                    <a href="<?= $table ?>.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<?php include '../_footer.php'; ?>
