<?php
include '../_header.php'; // Menyertakan header
include '../config/config.php';

$id = $_GET['id']; // Mendapatkan ID dari URL
$notification = "";
$table = $_GET['table'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $hm_awal = $_POST['hm_awal'];
    $hm_akhir = $_POST['hm_akhir'];
    $keterangan = $_POST['keterangan'];

    // Menghitung jumlah_hm
    $jumlah_hm = $hm_akhir - $hm_awal;

    // Validasi panjang keterangan dan pengguna
    if (strlen($keterangan) > 100) {
        $notification = "<div class='alert alert-danger'>Keterangan tidak boleh lebih dari 100 karakter.</div>";
    } else {
        $sql = "UPDATE $table SET tanggal=?, hm_awal=?, hm_akhir=?, jumlah_hm=?, keterangan=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdddss", $tanggal, $hm_awal, $hm_akhir, $jumlah_hm, $keterangan, $id);

        try {
            if ($stmt->execute()) {
                $notification = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            // Deteksi error terkait trigger
            if (strpos($e->getMessage(), "Can't update table") !== false) {
                $notification = "<div class='alert alert-danger'>Nama <b>Pengguna</b> tidak bisa diubah karena terdapat <b>Angsuran</b> dengan nama tersebut.</div>";
            } else {
                $notification = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        }
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
            Edit Data Pemakaian HM
        </div>

        <div class="card-body">
            <form action="" method="POST" onsubmit="return validateForm()">
                <div id="data-container">
                    <div class="data-entry">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal :</label>
                                <input type="date" class="form-control" name="tanggal"
                                    value="<?= htmlspecialchars($data['tanggal']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pengguna :</label>
                                <p class="form-control"><?= htmlspecialchars($data['pengguna']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">HM Awal :</label>
                                <input class="form-control" name="hm_awal" type="number" step="0.01"
                                    value="<?= htmlspecialchars($data['hm_awal']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">HM Akhir :</label>
                                <input type="number" step="0.01" class="form-control" name="hm_akhir"
                                    value="<?= htmlspecialchars($data['hm_akhir']) ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3 ">
                                <label class="form-label">ID :</label>
                                <p class="form-control"><?= htmlspecialchars($data['no_hp']) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga / HM :</label>
                                    <p class="form-control"><?= htmlspecialchars($data['harga_hm']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Keterangan :</label>
                                <input type="text" class="form-control" name="keterangan"
                                    value="<?= htmlspecialchars($data['keterangan']) ?>" maxlength="100">
                            </div>
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

<script>
function validateForm() {
    const hmAwal = document.querySelector('input[name="hm_awal"]').value;
    const hmAkhir = document.querySelector('input[name="hm_akhir"]').value;

    if (parseFloat(hmAwal) >= parseFloat(hmAkhir)) {
        alert('HM Akhir harus lebih besar dari HM Awal.');
        return false;
    }
    return true;
}
</script>

<?php include '../_footer.php'; ?>
