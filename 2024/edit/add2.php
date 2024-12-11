<?php include '../_header.php'; ?>

<?php

include '../config/config.php';

$notification = ''; // Inisialisasi variabel notifikasi

// Menangani pengiriman form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];

    // Insert data ke database
    $sql = "INSERT INTO karyawan (nama, jabatan) VALUES ('$nama', '$jabatan')";

    if ($conn->query($sql) === TRUE) {
        $notification = "<div class='alert alert-success'>Data berhasil ditambahkan!</div>";
    } else {
        $notification = "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

$conn->close();
?>

<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            <b>Tambah Data Karyawan</b>
        </div>
        <div class="card-body">
            <form method="post" action="add2.php">
                <div id="data-container">
                    <div class="data-entry">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama:</label><br>
                                <input type="text" id="nama" name="nama" class="form-control" required><br><br>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jabatan" class="form-label">Jabatan:</label><br>
                                <input type="text" id="jabatan" name="jabatan" class="form-control" required><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
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
