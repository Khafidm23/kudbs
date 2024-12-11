<?php include '../_header.php'; ?>

<?php
include '../config/config.php';
require "../../_assets/libs/vendor/autoload.php";

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

$notification = "";
$table = $_GET['table']; // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['tb_pinjaman']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    die("Tabel tidak valid.");
}

if (isset($_POST['bsimpan'])) {
    $uuid = Uuid::uuid4()->toString();
    $tanggal = $_POST['tanggal'][0];
    $nama = $_POST['nama'][0];
    $no_hp = $_POST['no_hp'][0];
    $pinjaman = $_POST['pinjaman'][0];
    $keterangan = $_POST['ket'][0];

    if (empty($tanggal) || empty($nama) || empty($no_hp) || empty($pinjaman)) {
        $notification = "<div class='alert alert-danger'>Data tidak lengkap.</div>";
    } else {
        $sql = "INSERT INTO $table (id, tanggal, nama, no_hp, pinjaman, keterangan) VALUES ('$uuid', '$tanggal', '$nama', '$no_hp', '$pinjaman', '$keterangan')";
        if (mysqli_query($conn, $sql)) {
            $notification = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
        } else {
            $notification = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}

?>


<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            Tambah Pinjaman Karyawan
        </div>
        <div class="card-body">
            <form action="" method="POST" onsubmit="return validateForm()">
                <div id="data-container">
                    <div class="data-entry">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal :</label>
                                <input name="tanggal[]" type="date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Peminjam :</label>
                                <input name="nama[]" class="form-control" maxlength="100" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor HP :</label>
                                <input name="no_hp[]" type="tel" placeholder="+6281234567890" pattern="^\+62\d{9,12}$"
                                    title="Masukkan nomor dengan format +62 diikuti 9-12 digit angka"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Pinjaman :</label>
                                <input name="pinjaman[]" type="number" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan :</label>
                            <input name="ket[]" class="form-control" maxlength="200">
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="text-center">
                    <hr>
                    <?php echo $notification; ?>
                    <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                    <button class="btn btn-danger" name="bkosongkan" type="reset">Kosongkan</button>
                    <a href="tb_pinjaman.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<script>

    function validateForm() {

        var inputs = document.querySelectorAll('input[required]');
        inputs.forEach(function (input) {
            if (!input.value) {
                input.setCustomValidity('Harap isi bidang ini.');
                isValid = false;
            } else {
                input.setCustomValidity('');
            }
        });
        return isValid;
    }
</script>

<?php include '../_footer.php'; ?>