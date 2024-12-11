<?php include '../_header.php'; ?>

<?php
include '../config/config.php';
require "../_assets/libs/vendor/autoload.php";

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

$notification = "";
$table = $_GET['table']; // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['tb_hmbs01']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    die("Tabel tidak valid.");
}

if (isset($_POST['bsimpan'])) {
    $jumlah_data = $_POST['jumlah_data'];
    $errors = [];

    for ($i = 0; $i < $jumlah_data; $i++) {
        $uuid = Uuid::uuid4()->toString();
        $tanggal = $_POST['tanggal'][$i];
        $pengguna = $_POST['pengguna'][$i];
        $no_hp = $_POST['no_hp'][$i];
        $hm_awal = $_POST['hm_awal'][$i];
        $hm_akhir = $_POST['hm_akhir'][$i];
        $harga_hm = $_POST['harga_hm'][$i];
        $keterangan = $_POST['ket'][$i];

        if (empty($tanggal) || empty($pengguna) || empty($no_hp) || empty($hm_awal) || empty($hm_akhir)) {
            $errors[] = "Data pada baris ke-" . ($i + 1) . " tidak lengkap.";
        } else {
            $sql = "INSERT INTO $table (id, tanggal, pengguna, no_hp, hm_awal, hm_akhir, harga_hm, keterangan) VALUES ('$uuid', '$tanggal', '$pengguna', '$no_hp', '$hm_awal', '$hm_akhir', '$harga_hm', '$keterangan')";
            if (!mysqli_query($conn, $sql)) {
                $errors[] = "Error pada baris ke-" . ($i + 1) . ": " . mysqli_error($conn);
            }
        }
    }

    if (empty($errors)) {
        $notification = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
    } else {
        foreach ($errors as $error) {
            $notification .= "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>


<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            Tambah Data Pemakaian HM
        </div>
        <div class="card-body">
            <form action="" method="POST" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label class="form-label">Jumlah Data :</label>
                    <input name="jumlah_data" type="number" class="form-control" id="jumlah_data" min="1" value="1">
                </div>
                <div id="data-container">
                    <div class="data-entry">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal :</label>
                                <input name="tanggal[]" type="date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">HM Awal :</label>
                                <input name="hm_awal[]" type="number" step="0.01" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pengguna :</label>
                                <input name="pengguna[]" class="form-control" maxlength="50" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">HM Akhir :</label>
                                <input name="hm_akhir[]" type="number" step="0.01" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="no_hp">No. HP:</label>
                                <input name="no_hp[]" id="no_hp" type="tel" placeholder="Contoh: 081234567890"
                                    pattern="08[1-9][0-9]{7,11}"
                                    title="Masukkan nomor dengan format 08 diikuti 8-12 digit angka"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga / HM :</label>
                                <input name="harga_hm[]" type="number" value="375000" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Keterangan :</label>
                                <input name="ket[]" class="form-control" maxlength="100">
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

<script>
    document.getElementById('jumlah_data').addEventListener('change', function () {
        var jumlahData = this.value;
        var dataContainer = document.getElementById('data-container');
        dataContainer.innerHTML = '';

        for (var i = 0; i < jumlahData; i++) {
            var dataEntry = document.createElement('div');
            dataEntry.classList.add('data-entry');
            dataEntry.innerHTML = `
            <div class="card-footer bg-info""><div class="text-center mb-0"><strong>Data ke-${i + 1}</strong></div></div>
            <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal :</label>
                                <input name="tanggal[]" type="date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">HM Awal :</label>
                                <input name="hm_awal[]" type="number" step="0.01" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6 mb-3">
                                <label class="form-label">Pengguna :</label>
                                <input name="pengguna[]" class="form-control" maxlength="50" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">HM Akhir :</label>
                                <input name="hm_akhir[]" type="number" step="0.01" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="no_hp">No. HP:</label>
                                <input name="no_hp[]" id="no_hp" type="tel" placeholder="Contoh: 081234567890"
                                    pattern="08[1-9][0-9]{7,11}"
                                    title="Masukkan nomor dengan format 08 diikuti 8-12 digit angka"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga / HM :</label>
                                <input name="harga_hm[]" type="number" value="375000" class="form-control">
                            </div>
                            
                        </div>
                        <div class="row">
                        <div class="mb-3">
                                <label class="form-label">Keterangan :</label>
                                <input name="ket[]" class="form-control" maxlength="100">
                            </div>
                        </div>
            <hr>
        `;
            dataContainer.appendChild(dataEntry);
        }
    });


    function validateForm() {
        var isValid = true;
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