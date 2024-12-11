<?php include '../_header.php'; ?>

<?php

include '../config/config.php';

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;


$notification = "";
$table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uuid = Uuid::uuid4()->toString();
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $hm_awal = mysqli_real_escape_string($conn, $_POST['hm_awal']);
    $hm_akhir = mysqli_real_escape_string($conn, $_POST['hm_akhir']);
    $fee_hm = mysqli_real_escape_string($conn, $_POST['fee_hm']);
    $gaji_pokok = mysqli_real_escape_string($conn, $_POST['gaji_pokok']);
    $tunjangan = mysqli_real_escape_string($conn, $_POST['tunjangan']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $sql = "INSERT INTO rekap_gaji (id, nama, tanggal, hm_awal, hm_akhir, fee_hm, gaji_pokok, tunjangan, keterangan) 
            VALUES ('$uuid', '$nama', '$tanggal', '$hm_awal', '$hm_akhir', '$fee_hm', '$gaji_pokok', '$tunjangan', '$keterangan')";

    if (mysqli_query($conn, $sql)) {
        $notification = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
    } else {
        foreach ($errors as $error) {
            $notification .= "<div class='alert alert-danger'>$error</div>";
        }
    }
}

?>

<div class="container">
<?php echo $notification; ?>
    <div class="card mt-3 shadow-sm">
        <div class="card-header bg-info text-light"><b>Tambah Data Rincian Gaji Operator</b></div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="hm_awal" class="form-label">HM Awal</label>
                    <input type="number" name="hm_awal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="hm_akhir" class="form-label">HM Akhir</label>
                    <input type="number" name="hm_akhir" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="fee_hm" class="form-label">Fee per HM</label>
                    <input type="number" step="0.01" name="fee_hm" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                    <input type="number" name="gaji_pokok" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="tunjangan" class="form-label">Tunjangan</label>
                    <input type="number" name="tunjangan" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="keteraangan" class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="rekap_gaji.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<?php include '../_footer.php'; ?>
