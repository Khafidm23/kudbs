<?php
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

include '../_header.php';

include '../../config/config.php';

$search_keyword = '';
if (isset($_GET['bcari'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['tcari']);
}

// Cek apakah tombol hapus pencarian ditekan
if (isset($_POST['hapus_pencarian'])) {
    $search_keyword = '';
}

$items_per_page = 50; // Jumlah item per halaman
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tb_tgpinjaman");
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $items_per_page);

$sql_tgpinjaman = "SELECT * FROM daftar_kontak WHERE 1=1";
if (!empty($search_keyword)) {
    $sql_tgpinjaman .= " AND (nama LIKE '%$search_keyword%')";
}

$sql_tgpinjaman .= " ORDER BY nama ASC";
$offset = ($current_page - 1) * $items_per_page;
$sql_tgpinjaman .= " LIMIT $items_per_page OFFSET $offset";

$result = mysqli_query($conn, $sql_tgpinjaman) or die(mysqli_error($conn));

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
?>

<div class="card mt-3 shadow-sm">
    <div class="card-header bg-info text-light">Datar Kontak</div>
    <div class="card-body">
        <div class="d-flex justify-content-between">
        <div><a href="download.php?table=daftar_kontak" class="btn btn-success">Download Daftar Kontak</a></div>
            <div class="col-md-6 mx-auto">
                <form method="GET" action="">
                    <div class="input-group mb-3">
                        <input type="text" name="tcari" class="form-control" placeholder="Masukkan Nama Kontak!"
                            value="<?= htmlspecialchars($search_keyword) ?>">
                        <button class="btn btn-primary" name="bcari" type="submit">Cari</button>
                        <button class="btn btn-warning" name="hapus_pencarian" type="submit">Hapus</button>
                    </div>
                </form>
            </div>
        </div>

        <form>
            <div class="table-responsive shadow-sm mb-3">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-header table-info">
                    <tr>
                            <th style="width: 50px; vertical-align: middle;">No.</th>
                            <th style="vertical-align: middle; width: 100px;">Nama</th>
                            <th style="vertical-align: middle; width: 100px;">No. HP</th>
                            <th style="vertical-align: middle; width: 100px;">Sumber</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result) > 0) {
                            while ($data = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td style="vertical-align: middle;"><?= $no++ ?>.</td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data['nama']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data['no_hp']) ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($data['sumber']) ?></td>
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
        </form>

        <div class="card-footer bg-info d-flex justify-content-between" style="height: 60px;">
            <div class="align-self-center" style="color: white;">
                Halaman ke-<?= htmlspecialchars($page) ?> dari <?= htmlspecialchars($total_pages) ?> halaman, Total data
                <?= htmlspecialchars($total_data) ?>.
            </div>

            <form method="GET" action="">
                <input type="hidden" name="tcari" value="<?= htmlspecialchars($search_keyword) ?>">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <button class="page-link" type="submit" name="page" value="<?= $page - 1 ?>"
                                aria-label="Previous">
                                <span aria-hidden="true">«</span>
                            </button>
                        </li>
                        <?php endif; ?>

                        <?php
            $range = 1; // Rentang halaman yang ditampilkan di sekitar halaman saat ini
            $start = max(1, $page - $range);
            $end = min($total_pages, $page + $range);

            if ($start > 1) {
                echo '<li class="page-item"><button class="page-link" type="submit" name="page" value="1">1</button></li>';
                if ($start > 2) {
                    echo '<li class="page-item"><span class="page-link">...</span></li>';
                }
            }

            for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <button class="page-link" type="submit" name="page" value="<?= $i ?>"><?= $i ?></button>
                        </li>
                        <?php endfor; ?>

                        <?php if ($end < $total_pages) {
                if ($end < $total_pages - 1) {
                    echo '<li class="page-item"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><button class="page-link" type="submit" name="page" value="' . $total_pages . '">' . $total_pages . '</button></li>';
            } ?>

                        <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <button class="page-link" type="submit" name="page" value="<?= $page + 1 ?>"
                                aria-label="Next">
                                <span aria-hidden="true">»</span>
                            </button>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </form>


        </div>
    </div>
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