<?php
include '../_header.php'; // Menyertakan header
include '../config/config.php';

$id = $_GET['id']; // Mendapatkan ID dari URL
$notification = "";
$table = $_GET['table'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $angsuran = $_POST['angsuran'];
    $keterangan = $_POST['keterangan'];

        $sql = "UPDATE $table SET tanggal=?, angsuran=?, keterangan=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdss", $tanggal, $angsuran, $keterangan, $id);

        if ($stmt->execute()) {
            $notification = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
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
            <b>Edit Data Angsuran </b>
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
                                <label class="form-label">Nama :</label>
                                    <p class="form-control"><?= htmlspecialchars($data['nama']) ?>
                            </div>
                            
                        </div>
                        <div class="row">
                        <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor HP :</label>
                                    <p class="form-control"><?= htmlspecialchars($data['no_hp']) ?>
                            </div>
                        <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Angsuran :</label>
                                <input type="number" class="form-control" name="angsuran"
                                    value="<?= htmlspecialchars($data['angsuran']) ?>">
                            </div>
                        </div>
                        <div class="row">
                        
                            <div class="col-md-15 mb-3">
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
                    <a href="tb_angsuran_pinjaman.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<?php include '../_footer.php'; ?>