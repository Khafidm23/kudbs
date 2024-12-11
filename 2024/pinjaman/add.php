<?php include '../_header.php'; ?>

<?php
include '../config/config.php';
require "../../_assets/libs/vendor/autoload.php";

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

$notification = "";
$table = $_GET['table']; // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['tb_angsuran_pinjaman']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    die("Tabel tidak valid.");
}


if (isset($_POST['bsimpan'])) {
    $uuid = Uuid::uuid4()->toString();
    $tanggal = $_POST['tanggal'][0];
    $nama = $_POST['nama'][0];
    $no_hp = $_POST['no_hp'][0];
    $angsuran = $_POST['angsuran'][0];
    $keterangan = $_POST['ket'][0];
    $sumber = $_POST['sumber'][0];

    if (empty($tanggal) || empty($nama) || empty($no_hp) || empty($angsuran) || empty($sumber)) {
        $notification = "<div class='alert alert-danger'>Data tidak lengkap.</div>";
    } else {
        $sql = "INSERT INTO $table (id, tanggal, nama, no_hp, angsuran, keterangan, sumber) VALUES ('$uuid', '$tanggal', '$nama', '$no_hp', '$angsuran', '$keterangan', '$sumber')";
        if (mysqli_query($conn, $sql)) {
            $notification = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
        } else {
            $notification = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}

$query_pinjaman = "
    SELECT nama, no_hp, pinjaman, sisa_pinjaman, 'tb_tgpinjaman' AS sumber
    FROM tb_tgpinjaman
    WHERE sisa_pinjaman > 0
";
$result_pinjaman = mysqli_query($conn, $query_pinjaman);

if (!$result_pinjaman) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            Tambah Angsuran Pinjaman Karyawan
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
                                <select name="nama[]" class="form-control nama-dropdown" required>
                                    <option value="">Pilih Pinjaman</option>
                                    <?php while ($row = mysqli_fetch_assoc($result_pinjaman)): ?>
                                    <option value="<?= htmlspecialchars($row['nama']) ?>"
                                    data-nomor="<?= htmlspecialchars($row['no_hp']) ?>"
                                    data-sumber="<?= htmlspecialchars($row['sumber']) ?>"
                                    data-pinjaman="<?= htmlspecialchars($row['sisa_pinjaman']) ?>">
                                        <?= htmlspecialchars($row['nama']) ?>
                                        <?= htmlspecialchars($row['no_hp']) ?> (
                                        <?= htmlspecialchars($row['sisa_pinjaman']) ?> )
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Angsuran :</label>
                                <input name="angsuran[]" type="number" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Keterangan :</label>
                                <input name="ket[]" class="form-control" maxlength="200">
                            </div>
                            <input name="no_hp[]" type="hidden" class="form-control" required>
                            <input name="sumber[]" type="hidden" class="form-control" required>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="text-center">
                    <hr>
                    <?php echo $notification; ?>
                    <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                    <button class="btn btn-danger" name="bkosongkan" type="reset">Kosongkan</button>
                    <a href="tb_angsuran_pinjaman.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<script>

document.addEventListener('change', function(event) {
    if (event.target.classList.contains('nama-dropdown')) {
        var dataEntry = event.target.closest('.data-entry');
        var nomorInput = dataEntry.querySelector('input[name="no_hp[]"]');
        var sumberInput = dataEntry.querySelector('input[name="sumber[]"]');
        var selectedOption = event.target.options[event.target.selectedIndex];

        // Isi input berdasarkan atribut data dari opsi terpilih
        nomorInput.value = selectedOption.getAttribute('data-nomor');
        sumberInput.value = selectedOption.getAttribute('data-sumber');
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