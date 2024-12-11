<?php include '../_header.php'; ?>

<?php
include '../../config/config.php';

$pengguna = ''; // Nama pengguna yang dipilih untuk rincian
$nomor = ''; // Nomor yang dipilih untuk rincian
$table = ''; // Nama tabel yang diklik
$id = ''; // ID yang diklik


if (isset($_GET['id']) && isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Ambil nama pengguna dari tabel yang diklik
    $query_pengguna = "SELECT nama FROM $table WHERE id = '$id'";
    $result_pengguna = mysqli_query($conn, $query_pengguna);
    if ($result_pengguna && mysqli_num_rows($result_pengguna) > 0) {
        $pengguna = mysqli_fetch_assoc($result_pengguna)['nama'];
    } else {
        echo "Nama tidak ditemukan.";
        exit;
    }
}

$query_tagihan = "
    SELECT sumber, pengguna, no_hp, jumlah_hm, harga_hm, total_tagihan, total_angsuran, sisa_tagihan
    FROM (
        SELECT 'tb_tgbs01' AS sumber, pengguna, no_hp, jumlah_hm, harga_hm, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs01 WHERE pengguna = '$pengguna' AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
        UNION ALL
        SELECT 'tb_tgbs02' AS sumber, pengguna, no_hp, jumlah_hm, harga_hm, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs02 WHERE pengguna = '$pengguna' AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
        UNION ALL
        SELECT 'tb_tgbs03' AS sumber, pengguna, no_hp, jumlah_hm, harga_hm, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs03 WHERE pengguna = '$pengguna' AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
        UNION ALL
        SELECT 'tb_tgbs04' AS sumber, pengguna, no_hp, jumlah_hm, harga_hm, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs04 WHERE pengguna = '$pengguna' AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
        UNION ALL
        SELECT 'tb_tgbs05' AS sumber, pengguna, no_hp, jumlah_hm, harga_hm, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs05 WHERE pengguna = '$pengguna' AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    ) AS combined
    ORDER BY pengguna ASC";

$result_tagihan = mysqli_query($conn, $query_tagihan) or die(mysqli_error($conn));


// Ambil rincian angsuran berdasarkan harga_hm dan no_hp yang sama
$query_angsuran = "
    SELECT pengguna, no_hp, tanggal, angsuran
    FROM tb_angsuran 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    ORDER BY tanggal ASC";

$result_angsuran = mysqli_query($conn, $query_angsuran) or die(mysqli_error($conn));

// Ambil rincian gabungan
$query_gabungan = "
    SELECT nama, no_hp, total_tagihan, total_angsuran, sisa_tagihan
    FROM $table 
    WHERE nama = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    ORDER BY nama ASC";

$result_gabungan = mysqli_query($conn, $query_gabungan) or die(mysqli_error($conn));
?>


<div class="mt-3">
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
                            <th style="vertical-align: middle; width: 150px;">Sumber</th>
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
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_tagihan['sumber']) ?></td>
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

<div class=" mt-3">
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

<div class=" mt-3">
    <div class="card shadow-sm mx-auto">
        <div class="card-header bg-info text-light"><b>Rincian Tagihan Gabungan A/n <?= htmlspecialchars($pengguna) ?></b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-header table-info">
                        <tr>
                            <th style="width: 50px; vertical-align: middle;">No</th>
                            <th style="vertical-align: middle;">Nama</th>
                            <th style="vertical-align: middle;">ID</th>
                            <th style="vertical-align: middle; width: 150px;">Total Tagihan</th>
                            <th style="vertical-align: middle; width: 150px;">Total Angsuran</th>
                            <th style="vertical-align: middle; width: 150px;">Sisa Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result_gabungan) > 0) {
                            while ($data_gabungan = mysqli_fetch_assoc($result_gabungan)) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_gabungan['nama']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_gabungan['no_hp']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_gabungan['total_tagihan']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_gabungan['total_angsuran']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data_gabungan['sisa_tagihan']) ?></td>
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

<div class=" mt-3">
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
