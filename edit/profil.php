<?php
include '../_header.php';

include '../config/config.php';

$sql_profil = "SELECT * FROM profil";
$profil = mysqli_query($conn, $sql_profil);

$sql_pengurus = "SELECT * FROM pengurus";
$pengurus = mysqli_query($conn, $sql_pengurus);

$sql_karyawan = "SELECT * FROM karyawan";
$karyawan = mysqli_query($conn, $sql_karyawan);
?>


<section id="profil-koperasi" class="col-md-10 mx-auto">
    <h2><b>Profil Koperasi <a href="add.php?table=profil" class="btn btn-success float-right mr-3">Tambah</a></b>
        <div class="float-right mr-3">
            <a href="../index.php" class="btn btn-warning">Kembali</a>
        </div>
    </h2>
    <table>
        <tbody>
            <?php

            if (mysqli_num_rows($profil) > 0) {
                while ($data = mysqli_fetch_array($profil)) { ?>
                    <tr>

                        <td style="vertical-align: middle; width: 300px;"> <b> <?= $data['label'] ?> </b> </td>
                        <td style="vertical-align: middle; width: 10px;"><?= $data['pemisah'] ?></td>
                        <td style="vertical-align: middle; "><?= $data['isi'] ?></td>
                        <td style="vertical-align: middle; width: 200px;">
                            <a href="edit.php?id=<?= $data['id'] ?>&table=profil" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $data['id'] ?>&table=profil" class="btn btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6" class="text-center">Data tidak ditemukan</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<section id="daftar-pengurus" class="col-md-10 mx-auto">
    <h2><b>Daftar Pengurus <a href="add.php?table=pengurus" class="btn btn-success float-right mr-3">Tambah</a></b></h2>
    <table>
        <tbody>
            <?php

            if (mysqli_num_rows($pengurus) > 0) {
                while ($data = mysqli_fetch_array($pengurus)) { ?>
                    <tr>

                        <td style="vertical-align: middle; width: 300px;"> <b> <?= $data['label'] ?> </b> </td>
                        <td style="vertical-align: middle; width: 10px;"><?= $data['pemisah'] ?></td>
                        <td style="vertical-align: middle;"><?= $data['isi'] ?></td>
                        <td style="vertical-align: middle; width: 200px;">
                            <a href="edit.php?id=<?= $data['id'] ?>&table=pengurus" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $data['id'] ?>&table=pengurus" class="btn btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6" class="text-center">Data tidak ditemukan</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<section id="daftar-karyawan" class="col-md-10 mx-auto">
    <h2><b>Daftar Karyawan <a href="add2.php" class="btn btn-success float-right mr-3">Tambah</a></b></h2>
    <table>
        <thead>
            <tr>
                <th style="width: 20px;">No.</th>
                <th style="width: 550px;">Nama</th>
                <th style="width: 550px;">Jabatan</th>
                <th style="width: 200px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($karyawan) > 0) {
                while ($data = mysqli_fetch_array($karyawan)) { ?>
                    <tr>
                        <td style="vertical-align: middle;"><?= $no++ ?></td>
                        <td style="vertical-align: middle;"><?= $data['nama'] ?></td>
                        <td style="vertical-align: middle;"><?= $data['jabatan'] ?></td>
                        <td style="vertical-align: middle; width: 200px;">
                            <a href="edit2.php?id=<?= $data['id'] ?>&table=karyawan" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $data['id'] ?>&table=karyawan" class="btn btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6" class="text-center">Data tidak ditemukan</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>



<?php include '../_footer.php'; ?>

<style>
    /* Reset dasar */
    body,
    h1,
    h2,
    p,
    ul,
    table {
        margin: 0;
        padding: 0;
        box-sizing: border-box;

    }

    /* Tampilan dasar */
    body {
        background-color: #f4f4f4;
        color: #333;
        line-height: 1.6;
    }

    header {
        text-align: center;
        padding: 10px 0;
        background-color: #4CAF50;
        color: white;
        margin-bottom: 20px;
    }

    h1 {
        margin-bottom: 0;
    }

    section {
        background-color: white;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h2 {
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-size: 24px;
    }

    ul {
        list-style: none;
        padding-left: 0;
    }

    ul li {
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tfoot {
        text-align: center;
        padding: 10px 0;
        margin-top: 20px;
        background-color: #4CAF50;
        color: white;
    }

    footer p {
        padding: 20px 0;
        margin: 0;
    }
</style>