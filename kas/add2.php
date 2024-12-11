<?php include '../_header.php'; ?>

<?php
include '../config/config.php';
require "../_assets/libs/vendor/autoload.php";

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

$notification = "";
$table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING); // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['tb_pengeluaran']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    die("Tabel tidak valid.");
}

if (isset($_POST['bsimpan'])) {
    $jumlah_data = $_POST['jumlah_data'];
    $errors = [];

    for ($i = 0; $i < $jumlah_data; $i++) {
        $uuid = Uuid::uuid4()->toString();
        $tanggal = $_POST['tanggal'][$i];
        $keterangan = $_POST['keterangan'][$i];
        $pengeluaran = $_POST['pengeluaran'][$i];

        if (empty($tanggal) || empty($keterangan) || empty($pengeluaran)) {
            $errors[] = "Data pada baris ke-" . ($i + 1) . " tidak lengkap.";
        } else {
            $sql = "INSERT INTO $table (id, tanggal, keterangan, pengeluaran) 
                    VALUES ('$uuid', '$tanggal', '$keterangan', '$pengeluaran')";
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
            Tambah Pengeluaran Kas
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
                                <label class="form-label">Jumlah Pengeluaran :</label>
                                <input name="pengeluaran[]" type="number"  class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-15 mb-3">
                                <label class="form-label">Keterangan :</label>
                                <input name="keterangan[]" type="text" class="form-control" maxlength="255" required>
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
                    <a href="<?=$table?>.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<script>
document.getElementById('jumlah_data').addEventListener('change', function() {
    var jumlahData = this.value;
    var dataContainer = document.getElementById('data-container');
    dataContainer.innerHTML = '';

    for (var i = 0; i < jumlahData; i++) {
        var dataEntry = document.createElement('div');
        dataEntry.classList.add('data-entry');
        dataEntry.innerHTML = `
            <div class="card-footer bg-info"><div class="text-center mb-0"><strong>Data ke-${i + 1}</strong></div></div>
            
            <div class="row mt-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal :</label>
                                <input name="tanggal[]" type="date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Keterangan :</label>
                                <input name="keterangan[]" type="text" class="form-control" maxlength="255" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Pengeluaran :</label>
                                <input name="pengeluaran[]" type="number"  class="form-control" required>
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
    inputs.forEach(function(input) {
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