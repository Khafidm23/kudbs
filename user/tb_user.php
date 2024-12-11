<?php include '../_header.php'; ?>

<?php
include '../config/config.php';

// Mulai query SQL
$sql_user = "SELECT * FROM tb_user";
$conditions = [];

// Gabungkan kondisi jika ada
if (count($conditions) > 0) {
    $sql_user .= " WHERE " . implode(" AND ", $conditions);
}

$order_by = 'username';
$order_direction = 'ASC';
$limit = 50;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$sql_user .= " ORDER BY $order_by $order_direction LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql_user);
?>

<div class="container mt-3">
    <div class="card shadow-sm  mx-auto">
        <div class="card-header bg-info text-light"><b>Management User</b></div>
        <div class="card-body mx-5">
            <form method="POST" action="del_all.php" onsubmit="return confirmDeleteAll()">
                <input type="hidden" name="table" value="tb_user">
                <input type="hidden" name="redirect" value="tb_user.php">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-header table-info">
                            <tr>
                                <th style="width: 50px; vertical-align: middle;">No.</th>
                                <th style="vertical-align: middle;">Username</th>
                                <th style="width: 150px;">
                                    <a href="add2.php?table=tb_user" class="btn btn-success">Tambah User</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $no = $offset + 1;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($data = mysqli_fetch_array($result)) { ?>
                            <tr>
                                <td style="vertical-align: middle;"><?= $no++ ?></td>
                                <td style="vertical-align: middle;"><?= $data['username'] ?></td>
                                <td>
                                    <a href="edit.php?id_user=<?= htmlspecialchars($data['id_user']) ?>&table=tb_user"
                                        class="btn btn-warning">Edit</a>
                                    <a href="delete.php?id_user=<?= htmlspecialchars($data['id_user']) ?>&table=tb_user"
                                        class="btn btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php }
                                } else { ?>
                            <tr>
                                <td colspan="3" class="text-center">Data user ditemukan</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
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
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>

<script>
function confirmDeleteAll() {
    return confirm('Apakah Anda yakin ingin menghapus semua data ini?');
}
</script>