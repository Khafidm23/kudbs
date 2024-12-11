<?php include '../_header.php'; ?>

<?php

include '../config/config.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$notification = ''; // Inisialisasi variabel notifikasi

// Ambil data dari database untuk ditampilkan di form
$sql = "SELECT * FROM karyawan WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    $notification = "<div class='alert alert-danger'>Data tidak ditemukan</div>";
    echo $notification;
    exit;
}

// Menangani pengiriman form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];

    // Update data ke database
    $sql = "UPDATE karyawan SET 
            nama='$nama',  
            jabatan='$jabatan'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $notification = "<div class='alert alert-success'>Data berhasil diupdate!</div>";

        // Ambil kembali data terbaru dari database setelah update
        $sql = "SELECT * FROM karyawan WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            $notification = "<div class='alert alert-danger'>Data tidak ditemukan setelah update</div>";
        }
    } else {
        $notification = "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

$conn->close();
?>

<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            <b>Edit Data Karyawan</b> <!-- Menampilkan nama tabel di judul -->
        </div>
        <div class="card-body">
            <form method="post" action="edit2.php?id=<?php echo $id; ?>&table=karyawan">
                <div id="data-container">
                    <div class="data-entry">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama:</label><br>
                                <input type="text" id="nama" name="nama" class="form-control"
                                    value="<?php echo htmlspecialchars($row['nama']); ?>"><br><br>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jabatan" class="form-label">Jabatan:</label><br>
                                <input type="text" id="jabatan" name="jabatan" class="form-control"
                                    value="<?php echo htmlspecialchars($row['jabatan']); ?>"><br><br>
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


