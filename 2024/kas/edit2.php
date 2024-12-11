<?php
include '../_header.php'; // Menyertakan header
include '../config/config.php';

$id = $_GET['id']; // Mendapatkan ID dari URL
$notification = "";
$table = $_GET['table'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $pengeluaran = $_POST['pengeluaran'];

        $sql = "UPDATE $table SET tanggal=?, keterangan=?,  pengeluaran=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $tanggal,  $keterangan, $pengeluaran, $id);

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
            <b>Edit Data pengeluaran </b>
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
                                <label class="form-label">Jumlah pengeluaran :</label>
                                <input type="number" class="form-control" name="pengeluaran"
                                    value="<?= htmlspecialchars($data['pengeluaran']) ?>">
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
                    <a href="<?= $table ?>.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<?php include '../_footer.php'; ?>