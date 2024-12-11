<?php include '../_header.php'; ?>

<?php
include '../config/config.php';

require "../_assets/libs/vendor/autoload.php";
use Ramsey\Uuid\Uuid;

$notification = "";
$table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING); // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['tb_user']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    die("Tabel tidak valid.");
}

if (isset($_POST['bsimpan'])) {

    $errors = [];

    // Asumsikan username dan password adalah array
    $usernames = $_POST['username'];
    $passwords = $_POST['password'];

    for ($i = 0; $i < count($usernames); $i++) {
        $uuid = Uuid::uuid4()->toString();
        $user = filter_var($usernames[$i], FILTER_SANITIZE_STRING);
        $pass = password_hash($passwords[$i], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT * FROM $table WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $checkUserResult = $stmt->get_result();

        if ($checkUserResult->num_rows > 0) {
            $errors[] = "Username sudah ada.";
            continue;
        }


        $stmt = $conn->prepare("INSERT INTO $table (id_user, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $uuid, $user, $pass);

        if (!$stmt->execute()) {
            $errors[] = "Error pada baris ke-" . ($i + 1) . ": " . $stmt->error;
        }

    }

    if (empty($errors)) {
        $notification = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
    } else {
        $notification = ''; // Pastikan $notification diinisialisasi
        foreach ($errors as $error) {
            $notification .= "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>

<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            <b>Tambah User</b>
        </div>
        <div class="card-body">
            <form action="" method="POST" onsubmit="return validateForm()">
                <div id="data-container">
                    <div class="data-entry">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username :</label>
                                <input name="username[]" type="text" class="form-control" maxlength="70" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password :</label>
                                <input name="password[]" type="password" class="form-control" maxlength="70" required>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="text-center">
                    <hr>
                    <?php echo $notification; ?>
                    <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                    <button class="btn btn-danger" name="bkosongkan" type="reset">Kosongkan</button>
                    <a href="<?= $table ?>.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<?php include '../_footer.php'; ?>