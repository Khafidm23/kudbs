<?php include '../_header.php'; ?>
<?php
include '../config/config.php';

// Inisialisasi variabel
$search_keyword = '';
$order_by = 'tanggal';
$order_direction = 'DESC';

// Cek apakah tombol cari ditekan
$is_search = isset($_GET['bcari']);
if ($is_search) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['tcari']);
}

// Cek apakah tombol tampilkan semua ditekan
if (isset($_POST['tampilkan_semua'])) {
    $selected_month = '';
    $selected_year = '';
    $order_by = 'tanggal';
    $order_direction = 'DESC';
    $search_keyword = '';
} else {
    $selected_month = isset($_POST['bulan']) ? intval($_POST['bulan']) : (isset($_GET['bulan']) ? intval($_GET['bulan']) : date('n'));
    $selected_year = isset($_POST['tahun']) ? intval($_POST['tahun']) : (isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y'));
    $order_by = isset($_POST['order_by']) ? mysqli_real_escape_string($conn, $_POST['order_by']) : (isset($_GET['order_by']) ? mysqli_real_escape_string($conn, $_GET['order_by']) : 'tanggal');
    $order_direction = isset($_POST['order_direction']) ? mysqli_real_escape_string($conn, $_POST['order_direction']) : (isset($_GET['order_direction']) ? mysqli_real_escape_string($conn, $_GET['order_direction']) : 'DESC');
}

// Limit data per halaman
$limit = 50;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Mulai query SQL
$sql_angsuran = "SELECT * FROM tb_angsuran_pinjaman";
$conditions = [];

// Jika tombol cari ditekan, gunakan pencarian tanpa filter bulan dan tahun
if ($is_search) {
    $conditions[] = "(nama LIKE '%$search_keyword%' OR tanggal LIKE '%$search_keyword%' OR sumber LIKE '%$search_keyword%' OR keterangan LIKE '%$search_keyword%')";
} else {
    // Jika tidak mencari, aplikasikan filter bulan dan tahun
    if (!isset($_POST['tampilkan_semua']) && ($selected_month || $selected_year)) {
        if ($selected_month) {
            $conditions[] = "MONTH(tanggal) = '$selected_month'";
        }
        if ($selected_year) {
            $conditions[] = "YEAR(tanggal) = '$selected_year'";
        }
    }

    // Tambahkan kondisi pencarian jika ada
    if (!empty($search_keyword)) {
        $conditions[] = "(nama LIKE '%$search_keyword%' OR tanggal LIKE '%$search_keyword%' OR sumber LIKE '%$search_keyword%' OR keterangan LIKE '%$search_keyword%')";
    }
}

// Gabungkan kondisi jika ada
if (count($conditions) > 0) {
    $sql_angsuran .= " WHERE " . implode(" AND ", $conditions);
}

// Hitung total data
$sql_total = str_replace('SELECT *', 'SELECT COUNT(*) AS total', $sql_angsuran);
$total_result = mysqli_query($conn, $sql_total);
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $limit);

// Tambahkan urutan dan batasan
$sql_angsuran .= " ORDER BY $order_by $order_direction LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql_angsuran);
?>

<div class="card mt-3 shadow-sm">
    <div class="card-header bg-info text-light"><b> Rekapan Angsuran Pinjaman </b></div>
    <div class="card-body">
        <div class="d-flex justify-content-between">
        <div><a href="angsuran.php?table=tb_angsuran_pinjaman" class="btn btn-success">Download Daftar Angsuran</a></div>
            <div class="col-md-6 mx-auto">
                <form method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="tcari" class="form-control" placeholder="Masukkan Kata Kunci!"
                            value="<?= htmlspecialchars($search_keyword) ?>">
                        <button class="btn btn-primary" name="bcari" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container col-auto ">
            <div class="card p-3 mb-3 shadow-sm">
                <form method="POST" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex">
                            <select name="bulan" class="form-select me-2">
                                <option value="">Pilih Bulan</option>
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                <option value="<?= $i ?>" <?= ($i == $selected_month) ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                                <?php } ?>
                            </select>
                            <select name="tahun" class="form-select me-2">
                                <option value="">Pilih Tahun</option>
                                <?php for ($i = 2020; $i <= date('Y'); $i++) { ?>
                                <option value="<?= $i ?>" <?= ($i == $selected_year) ? 'selected' : '' ?>><?= $i ?>
                                </option>
                                <?php } ?>
                            </select>
                            <button class="btn btn-primary" type="submit">Tampilkan</button>
                        </div>
                        <div>
                            <button class="btn btn-secondary" name="tampilkan_semua" type="submit">Tampilkan
                                Semua</button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex">
                            <select name="order_by" class="form-select me-2">
                                <option value="tanggal" <?= ($order_by == 'tanggal') ? 'selected' : '' ?>>Tanggal
                                </option>
                                <option value="nama" <?= ($order_by == 'nama') ? 'selected' : '' ?>>Nama
                                </option>
                            </select>
                            <select name="order_direction" class="form-select me-2">
                                <option value="ASC" <?= ($order_direction == 'ASC') ? 'selected' : '' ?>>Ascending
                                </option>
                                <option value="DESC" <?= ($order_direction == 'DESC') ? 'selected' : '' ?>>Descending
                                </option>
                            </select>
                            <button class="btn btn-primary" type="submit">Urutkan</button>
                        </div>
                        <div>
                            <a href="add.php?table=tb_angsuran_pinjaman" class="btn btn-success">Tambah Angsuran</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <form method="POST" action="del_all.php" onsubmit="return confirmDeleteAll()">
            <input type="hidden" name="table" value="tb_angsuran_pinjaman">
            <input type="hidden" name="redirect" value="tb_angsuran_pinjaman.php">
            <div class="table-responsive shadow-sm mb-2">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-header table-info">
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th style="width: 120px;">Tanggal</th>
                            <th>Nama</th>
                            <th>Nomor HP</th>
                            <th style="width: 250px;">Jumlah Angsuran</th>
                            <th>Keterangan</th>
                            <th style="width: 170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no = $offset + 1;
                            if (mysqli_num_rows($result) > 0) {
                                while ($data = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td style="vertical-align: middle;"><?= $no++ ?></td>
                            <td style="vertical-align: middle;"><?= $data['tanggal'] ?></td>
                            <td style="vertical-align: middle;"><?= $data['nama'] ?></td>
                            <td style="vertical-align: middle;"><?= $data['no_hp'] ?></td>
                            <td style="vertical-align: middle;"><?= $data['angsuran'] ?></td>
                            <td style="vertical-align: middle;"><?= $data['keterangan'] ?></td>
                            <td>
                                <a href="edit.php?id=<?= $data['id'] ?>&table=tb_angsuran_pinjaman"
                                    class="btn btn-warning">Edit</a>
                                <a href="delete.php?id=<?= $data['id'] ?>&table=tb_angsuran_pinjaman" class="btn btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                <input type="checkbox" name="del_all[]" value="<?= $data['id'] ?>">
                            </td>
                        </tr>
                        <?php }
                            } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- Tambahkan tombol "Check All" di sebelah tombol "Del All" -->
            <div style="text-align: right;" class="mb-2">
                <button type="button" class="btn btn-secondary" id="checkAll">Check All</button>
                <button type="submit" class="btn btn-danger" id="deleteAll" disabled>Del All</button>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="del_all[]"]');
    const deleteButton = document.getElementById('deleteAll');
    const checkAllButton = document.getElementById('checkAll');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            deleteButton.disabled = !anyChecked;
        });
    });

    checkAllButton.addEventListener('click', function() {
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });
        deleteButton.disabled = !Array.from(checkboxes).some(checkbox => checkbox.checked);
    });
});

function confirmDeleteAll() {
    return confirm('Apakah Anda yakin ingin menghapus semua data yang dipilih?');
}
</script>
<?php include '../_footer.php'; ?>

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
    max-width: 250px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>