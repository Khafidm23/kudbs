<?php
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

include '../_header.php';

include '../config/config.php';

$items_per_page = 50; // Jumlah item per halaman
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM log");
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $items_per_page);

$sql_log = "SELECT * FROM log WHERE 1=1";
$sql_log .= " ORDER BY waktu DESC";
$offset = ($current_page - 1) * $items_per_page;
$sql_log .= " LIMIT $items_per_page OFFSET $offset";

$result = mysqli_query($conn, $sql_log) or die(mysqli_error($conn));

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
?>

<div class="card mt-3 shadow-sm">
    <div class="card-header bg-info text-light d-flex justify-content-between">
        <span>LOG</span>
        <form method="POST" action="del_all.php" onsubmit="return confirmDeleteAll()">
            <button type="submit" class="btn btn-danger">Delete All</button>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive shadow-sm mb-3">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-header table-info">
                    <tr>
                        <th style="width: 50px; vertical-align: middle;">No.</th>
                        <th style="vertical-align: middle; width: 100px;">Tabel</th>
                        <th style="vertical-align: middle; width: 100px;">Operasi</th>
                        <th style="vertical-align: middle; width: 100px;">Waktu</th>
                        <th style="vertical-align: middle;">Rincian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        if (mysqli_num_rows($result) > 0) {
                            while ($data = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="vertical-align: middle;"><?= $no++ ?>.</td>
                        <td style="vertical-align: middle;"><?= htmlspecialchars($data['tabel']) ?></td>
                        <td style="vertical-align: middle;"><?= htmlspecialchars($data['operasi']) ?></td>
                        <td style="vertical-align: middle;"><?= htmlspecialchars($data['waktu']) ?></td>
                        <td style="vertical-align: middle; word-wrap: break-word;"><?= htmlspecialchars($data['rincian']) ?></td>
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
    <div class="card-footer bg-info d-flex justify-content-between">
        <div class="align-self-center text-white">
            Halaman ke-<?= htmlspecialchars($page) ?> dari <?= htmlspecialchars($total_pages) ?> halaman, Total data
            <?= htmlspecialchars($total_data) ?>.
        </div>
        <form method="GET" action="">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <button class="page-link" type="submit" name="page" value="<?= $page - 1 ?>" aria-label="Previous">
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
                        <button class="page-link" type="submit" name="page" value="<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </button>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </form>
    </div>
</div>

<?php
mysqli_close($conn);
include '../_footer.php';
?>

<script>
function confirmDeleteAll() {
    return confirm('Apakah Anda yakin ingin menghapus semua data log?');
}
</script>

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

.table td:last-child {
    max-width: none;
    white-space: normal;
    word-wrap: break-word;
}
</style>
