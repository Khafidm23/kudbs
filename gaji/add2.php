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
    $gaji_pokok = mysqli_real_escape_string($conn, $_POST['gaji_pokok']);
    $tunjangan = mysqli_real_escape_string($conn, $_POST['tunjangan']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $sql = "INSERT INTO helper (id, nama, tanggal, gaji_pokok, tunjangan, keterangan) 
            VALUES ('$uuid', '$nama', '$tanggal', '$gaji_pokok', '$tunjangan', '$keterangan')";

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
        <div class="card-header bg-info text-light"><b>Tambah Data Rincian Gaji Helper</b></div>
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
                <a href="helper.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<?php include '../_footer.php'; ?>
