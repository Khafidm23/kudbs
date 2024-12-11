<?php include '../_header.php'; ?>

<?php

include '../config/config.php';

$notification = ''; // Inisialisasi variabel notifikasi
$table = isset($_GET['table']) && in_array($_GET['table'], ['profil', 'pengurus']) ? $_GET['table'] : 'profil';

// Menangani pengiriman form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $label = $_POST['label'];
    $isi = $_POST['isi'];

    // Insert data ke database
    $sql = "INSERT INTO $table (label, isi) VALUES ('$label', '$isi')";

    if ($conn->query($sql) === TRUE) {
        $notification = "<div class='alert alert-success'>Data berhasil ditambahkan ke tabel " . ucfirst($table) . "!</div>";
    } else {
        $notification = "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

$conn->close();
?>

<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            <b>Tambah Data <?php echo ucfirst($table); ?></b>
        </div>
        <div class="card-body">
            <form method="post" action="add.php?table=<?php echo $table; ?>">
                <div id="data-container">
                    <div class="data-entry">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="label" class="form-label">Label:</label><br>
                                <input type="text" id="label" name="label" class="form-control" required><br><br>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="isi" class="form-label">Isi:</label><br>
                                <input type="text" id="isi" name="isi" class="form-control" required><br><br>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="text-center">
                    <hr>
                    <?php if (!empty($notification)) { echo $notification; } ?>
                    <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                    <a href="profil.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<?php include '../_footer.php'; ?>
