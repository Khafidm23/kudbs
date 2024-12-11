<?php include '../_header.php'; ?>

<?php

include '../config/config.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$table = isset($_GET['table']) ? $_GET['table'] : 'profil';  // Default table 'profil' jika parameter tidak ada

$notification = ''; // Inisialisasi variabel notifikasi

// Menangani pengiriman form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $label = $_POST['label'];
    $isi = $_POST['isi'];

    // Update data ke database
    $sql = "UPDATE $table SET 
            label='$label',  
            isi='$isi'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $notification = "<div class='alert alert-success'>Data berhasil diupdate!</div>";

        // Ambil kembali data terbaru dari database setelah update
        $sql = "SELECT * FROM $table WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            $notification = "<div class='alert alert-danger'>Data tidak ditemukan setelah update</div>";
        }
    } else {
        $notification = "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Ambil data dari database untuk ditampilkan di form pertama kali
    $sql = "SELECT * FROM $table WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $notification = "<div class='alert alert-danger'>Data tidak ditemukan</div>";
        echo $notification;
        exit;
    }
}

$conn->close();
?>

<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            <b>Edit Data <?php echo ucfirst($table); ?></b> <!-- Menampilkan nama tabel di judul -->
        </div>
        <div class="card-body">
            <form method="post" action="edit.php?id=<?php echo $id; ?>&table=<?php echo $table; ?>">
                <div id="data-container">
                    <div class="data-entry">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="label" class="form-label">Label:</label><br>
                                <input type="text" id="label" name="label" class="form-control"
                                    value="<?php echo $row['label']; ?>"><br><br>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="isi" class="form-label">Isi:</label><br>
                                <input type="text" id="isi" name="isi" class="form-control"
                                    value="<?php echo $row['isi']; ?>"><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <?php if (!empty($notification)) { echo "<p>$notification</p>"; } ?>
                    <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                    <a href="profil.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<?php include '../_footer.php'; ?>
