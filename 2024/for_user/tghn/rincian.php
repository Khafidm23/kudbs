<?php include '../_header.php'; ?>

<?php
include '../../config/config.php';

$pengguna = ''; // Nama pengguna yang dipilih untuk rincian
$table = ''; // Nama tabel yang diklik
$id = ''; // ID yang diklik

if (isset($_GET['id']) && isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Ambil nama pengguna dari tabel yang diklik
    $query_pengguna = "SELECT pengguna FROM $table WHERE id = '$id'";
    $result_pengguna = mysqli_query($conn, $query_pengguna);
    if ($result_pengguna && mysqli_num_rows($result_pengguna) > 0) {
        $pengguna = mysqli_fetch_assoc($result_pengguna)['pengguna'];
    } else {
        echo "Pengguna tidak ditemukan.";
        exit;
    }
}

// Ambil rincian HM dengan harga_hm dan no_hp yang sama
$query_hm = "
    SELECT 'tb_hmbs01' AS sumber, pengguna, no_hp, tanggal, jumlah_hm, harga_hm 
    FROM tb_hmbs01 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    AND harga_hm = (
        SELECT harga_hm FROM $table WHERE id = '$id'
    )
    UNION ALL
    SELECT 'tb_hmbs02' AS sumber, pengguna, no_hp, tanggal, jumlah_hm, harga_hm 
    FROM tb_hmbs02 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    AND harga_hm = (
        SELECT harga_hm FROM $table WHERE id = '$id'
    )
    UNION ALL
    SELECT 'tb_hmbs03' AS sumber, pengguna, no_hp, tanggal, jumlah_hm, harga_hm 
    FROM tb_hmbs03 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    AND harga_hm = (
        SELECT harga_hm FROM $table WHERE id = '$id'
    )
    UNION ALL
    SELECT 'tb_hmbs04' AS sumber, pengguna, no_hp, tanggal, jumlah_hm, harga_hm 
    FROM tb_hmbs04 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    AND harga_hm = (
        SELECT harga_hm FROM $table WHERE id = '$id'
    )
    UNION ALL
    SELECT 'tb_hmbs05' AS sumber, pengguna, no_hp, tanggal, jumlah_hm, harga_hm 
    FROM tb_hmbs05 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    AND harga_hm = (
        SELECT harga_hm FROM $table WHERE id = '$id'
    )
    ORDER BY tanggal ASC";

$result_hm = mysqli_query($conn, $query_hm) or die(mysqli_error($conn));

// Ambil rincian angsuran berdasarkan harga_hm dan no_hp yang sama
$query_angsuran = "
    SELECT pengguna, no_hp, tanggal, angsuran
    FROM tb_angsuran 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    AND harga_hm = (
        SELECT harga_hm FROM $table WHERE id = '$id'
    )
    ORDER BY tanggal ASC";

$result_angsuran = mysqli_query($conn, $query_angsuran) or die(mysqli_error($conn));

// Ambil rincian tagihan
$query_tagihan = "
    SELECT pengguna, no_hp, jumlah_hm, harga_hm, total_tagihan, total_angsuran, sisa_tagihan
    FROM $table 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    AND harga_hm = (
        SELECT harga_hm FROM $table WHERE id = '$id'
    )
    ORDER BY pengguna ASC";

$result_tagihan = mysqli_query($conn, $query_tagihan) or die(mysqli_error($conn));
?>


<div class="container mt-3">
    <div class="card shadow-sm mx-auto">
        <div class="card-header bg-info text-light"><b>Rincian HM A/n <?= htmlspecialchars($pengguna) ?></b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-header table-info">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Jumlah HM</th>
                            <th>Harga HM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result_hm) > 0) {
                            while ($data_hm = mysqli_fetch_assoc($result_hm)) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($data_hm['pengguna']) ?></td>
                                    <td><?= htmlspecialchars($data_hm['no_hp']) ?></td>
                                    <td><?= htmlspecialchars($data_hm['tanggal']) ?></td>
                                    <td><?= htmlspecialchars($data_hm['jumlah_hm']) ?></td>
                                    <td><?= htmlspecialchars($data_hm['harga_hm']) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="6" class="text-center">Data tidak ditemukan</td>
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
        <div class="card-header bg-info text-light"><b>Rincian Angsuran A/n <?= htmlspecialchars($pengguna) ?></b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-header table-info">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>ID</th>
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
                                    <td><?= htmlspecialchars($data_angsuran['pengguna']) ?></td>
                                    <td><?= htmlspecialchars($data_angsuran['no_hp']) ?></td>
                                    <td><?= htmlspecialchars($data_angsuran['tanggal']) ?></td>
                                    <td><?= htmlspecialchars($data_angsuran['angsuran']) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="5" class="text-center">Data tidak ditemukan</td>
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
        <div class="card-header bg-info text-light"><b>Rincian Tagihan A/n <?= htmlspecialchars($pengguna) ?></b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-header table-info">
                        <tr>
                            <th style="width: 50px; vertical-align: middle;">No</th>
                            <th style="vertical-align: middle;">Nama</th>
                            <th style="vertical-align: middle;">ID</th>
                            <th style="vertical-align: middle; width: 100px;">Jumlah HM</th>
                            <th style="vertical-align: middle; width: 100px;">Harga / HM</th>
                            <th style="vertical-align: middle; width: 150px;">Jumlah Tagihan</th>
                            <th style="vertical-align: middle; width: 150px;">Jumlah Angsuran</th>
                            <th style="vertical-align: middle; width: 150px;">Sisa Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result_tagihan) > 0) {
                            while ($data_tagihan = mysqli_fetch_assoc($result_tagihan)) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_tagihan['pengguna']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_tagihan['no_hp']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_tagihan['jumlah_hm']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_tagihan['harga_hm']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_tagihan['total_tagihan']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_tagihan['total_angsuran']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_tagihan['sisa_tagihan']) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="8" class="text-center">Data tidak ditemukan</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <a href="<?= htmlspecialchars($table) ?>.php" class="btn btn-warning">Kembali</a>
    <a href="download_excel.php?pengguna=<?= urlencode($pengguna) ?>&table=<?= urlencode($table) ?>&id=<?= urlencode($id) ?>" class="btn btn-success">Download Rincian Excel</a>
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
    }

    .table td,
    .table th {
        white-space: nowrap;
    }

    .table td {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
