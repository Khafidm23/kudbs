<?php
include '../_header.php'; // Menyertakan header
include '../config/config.php';

$id = $_GET['id']; // Mendapatkan ID dari URL
$notification = "";
$table = 'rekap_gaji'; // Nama tabel yang digunakan

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $hm_awal = $_POST['hm_awal'];
    $hm_akhir = $_POST['hm_akhir'];
    $fee_hm = $_POST['fee_hm'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $tunjangan = $_POST['tunjangan'];
    $keterangan = $_POST['keterangan'];


    $sql = "UPDATE $table SET nama=?, tanggal=?, hm_awal=?, hm_akhir=?, fee_hm=?, gaji_pokok=?, tunjangan=?, keterangan=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddddss", $nama, $tanggal, $hm_awal, $hm_akhir, $fee_hm, $gaji_pokok, $tunjangan, $keterangan, $id);

    if ($stmt->execute()) {
        $notification = "<div class='alert alert-success'>Data berhasil diperbarui!</div>";
    } else {
        $error = $stmt->error;
        $notification = "<div class='alert alert-danger'>$error</div>";
    }
}

$sql = "SELECT * FROM $table WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            <b>Edit Data Rincian Gaji</b>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama :</label>
                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal :</label>
                    <input type="date" class="form-control" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">HM Awal :</label>
                    <input type="number" class="form-control" name="hm_awal" value="<?= htmlspecialchars($data['hm_awal']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">HM Akhir :</label>
                    <input type="number" class="form-control" name="hm_akhir" value="<?= htmlspecialchars($data['hm_akhir']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fee / HM :</label>
                    <input type="number" class="form-control" name="fee_hm" value="<?= htmlspecialchars($data['fee_hm']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gaji Pokok :</label>
                    <input type="number" class="form-control" name="gaji_pokok" value="<?= htmlspecialchars($data['gaji_pokok']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tunjangan :</label>
                    <input type="number" class="form-control" name="tunjangan" value="<?= htmlspecialchars($data['tunjangan']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan :</label>
                    <input type="text" class="form-control" name="keterangan" value="<?= htmlspecialchars($data['keterangan']) ?>" maxlength="100">
                </div>
                <div class="text-center">
                    <?php echo $notification; ?>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="rekap_gaji.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<?php include '../_footer.php'; ?>
