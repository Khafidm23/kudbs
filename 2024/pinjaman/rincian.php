<?php include '../_header.php'; ?>

<?php
include '../config/config.php';

$nama = ''; // Nama nama yang dipilih untuk rincian
if (isset($_GET['id']) && isset($_GET['table'])) {
    // Ambil nama nama dari tabel yang diklik
    $table = mysqli_real_escape_string($conn, $_GET['table']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query_nama = "SELECT nama FROM $table WHERE id = '$id'";
    $result_nama = mysqli_query($conn, $query_nama);
    if ($result_nama && mysqli_num_rows($result_nama) > 0) {
        $nama = mysqli_fetch_assoc($result_nama)['nama'];
    }
}

// Rincian HM
$query_pinjaman = "
    SELECT nama, tanggal, pinjaman FROM tb_pinjaman WHERE nama = '$nama'
    ORDER BY tanggal ASC";

$result_pinjaman= mysqli_query($conn, $query_pinjaman) or die(mysqli_error($conn));

// Rincian Angsuran
$query_angsuran = "SELECT nama, tanggal, angsuran FROM tb_angsuran_pinjaman WHERE nama = '$nama'";
$result_angsuran = mysqli_query($conn, $query_angsuran) or die(mysqli_error($conn));

// Rincian Tagihan
$query_tagihan = "SELECT nama, pinjaman, angsuran, sisa_pinjaman FROM tb_tgpinjaman WHERE nama = '$nama'";
$result_tagihan = mysqli_query($conn, $query_tagihan) or die(mysqli_error($conn));
?>

<div class="container mt-3">
    <div class="card shadow-sm mx-auto">
        <div class="card-header bg-info text-light"><b>Rincian Pinjaman A/n <?= htmlspecialchars($nama) ?></b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-header table-info">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal Pinjaman</th>
                            <th>Jumlah Pinjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result_pinjaman) > 0) {
                            while ($data_pinjaman = mysqli_fetch_assoc($result_pinjaman)) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($data_pinjaman['nama']) ?></td>
                                    <td><?= htmlspecialchars($data_pinjaman['tanggal']) ?></td>
                                    <td><?= htmlspecialchars($data_pinjaman['pinjaman']) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="4" class="text-center">Data tidak ditemukan</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="card shadow-sm mx-auto">
        <div class="card-header bg-info text-light"><b>Rincian Angsuran A/n <?= htmlspecialchars($nama) ?></b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-header table-info">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal Angsuran</th>
                            <th>Jumlah Angsuran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result_angsuran) > 0) {
                            while ($data_angsuran = mysqli_fetch_assoc($result_angsuran)) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($data_angsuran['nama']) ?></td>
                                    <td><?= htmlspecialchars($data_angsuran['tanggal']) ?></td>
                                    <td><?= htmlspecialchars($data_angsuran['angsuran']) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="4" class="text-center">Data tidak ditemukan</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="card shadow-sm mx-auto">
        <div class="card-header bg-info text-light"><b>Rincian Tagihan A/n <?= htmlspecialchars($nama) ?></b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-header table-info">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumah Pinjaman</th>
                            <th>Jumlah Angsuran</th>
                            <th>Sisa Pinjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result_tagihan) > 0) {
                            while ($data_tagihan = mysqli_fetch_assoc($result_tagihan)) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($data_tagihan['nama']) ?></td>
                                    <td><?= htmlspecialchars($data_tagihan['pinjaman']) ?></td>
                                    <td><?= htmlspecialchars($data_tagihan['angsuran']) ?></td>
                                    <td><?= htmlspecialchars($data_tagihan['sisa_pinjaman']) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="4" class="text-center">Data tidak ditemukan</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <a href="tagihan_pinjaman.php" class="btn btn-warning">Kembali</a>
</div>

<?php
mysqli_close($conn);
include '../_footer.php';
?>
<style>
.table-responsive {
    overflow-x: auto;
}

.table {
    table-layout: auto;
    /* Membiarkan browser menentukan lebar kolom berdasarkan konten */
}

.table td,
.table th {
    white-space: nowrap;
    /* Mencegah teks membungkus ke baris berikutnya */
}

.table td {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
