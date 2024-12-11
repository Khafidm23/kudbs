<?php
session_start();
require "../_assets/libs/vendor/autoload.php";
if (!isset($_SESSION['session_username'])) {
    header("Location: auth/login.php");
    exit();
}
?>
<?php
include 'config/config.php';

$sql_profil = "SELECT * FROM profil";
$profil = mysqli_query($conn, $sql_profil);

$sql_pengurus = "SELECT * FROM pengurus";
$pengurus = mysqli_query($conn, $sql_pengurus);

$sql_karyawan = "SELECT * FROM karyawan";
$karyawan = mysqli_query($conn, $sql_karyawan);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KUD_BONDO_SEPOLO</title>

    <!-- Custom fonts for this template-->
    <link href="../_assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../_assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">
                    <?php if (isset($_COOKIE['cookie_username'])) {
                        echo htmlspecialchars($_COOKIE['cookie_username']);
                    } else {
                        echo "admin";
                    } ?>
                </div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - KUD Bondo Sepolo -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <span>KUD Bondo Sepolo</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                LAPORAN JASA EXCAVATOR
            </div>
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <span>Rekapan HM</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Rekapan HM:</h6>
                        <a class="collapse-item" href="hm/upload_hm.php">Upload HM</a>

                        <a class="collapse-item" href="hm/tb_hmbs01.php">HM BS 01</a>
                        <a class="collapse-item" href="hm/tb_hmbs02.php">HM BS 02</a>
                        <a class="collapse-item" href="hm/tb_hmbs03.php">HM BS 03</a>
                        <a class="collapse-item" href="hm/tb_hmbs04.php">HM BS 04</a>
                        <a class="collapse-item" href="hm/tb_hmbs05.php">HM BS 05</a>
                    </div>
            </li>
            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <span>Tagihan</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Tagihan :</h6>
                        <a class="collapse-item" href="tghn/tb_tgbs01.php">Tagihan BS 01</a>
                        <a class="collapse-item" href="tghn/tb_tgbs02.php">Tagihan BS 02</a>
                        <a class="collapse-item" href="tghn/tb_tgbs03.php">Tagihan BS 03</a>
                        <a class="collapse-item" href="tghn/tb_tgbs04.php">Tagihan BS 04</a>
                        <a class="collapse-item" href="tghn/tb_tgbs05.php">Tagihan BS 05</a>
                        <a class="collapse-item" href="tghn/tagihan_gabungan.php">Tagihan Gabungan</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                KAS
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsetree"
                    aria-expanded="true" aria-controls="collapsetree">
                    <span>Pemasukan & Pengeluaran</span>
                </a>
                <div id="collapsetree" class="collapse" aria-labelledby="headingtree" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Kas :</h6>
                        <a class="collapse-item" href="kas/tb_angsuran.php">Angsuran HM</a>
                        <a class="collapse-item" href="kas/tb_pengeluaran.php">Pengeluaran</a>
                        <a class="collapse-item" href="kas/upload_pengeluaran.php">Upload Pengeluaran</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                GAJI & PINJAMAN KARYAWAN
            </div>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsefour"
                    aria-expanded="true" aria-controls="collapsefour">
                    <span>Rekapan Gaji Karyawan</span>
                </a>
                <div id="collapsefour" class="collapse" aria-labelledby="headingfour" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Gaji Karyawan :</h6>
                        <a class="collapse-item" href="gaji/rekap_gaji.php">Operator</a>
                        <a class="collapse-item" href="gaji/helper.php">Helper</a>
                    </div>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsefive"
                    aria-expanded="true" aria-controls="collapsefive">
                    <span>Pinjaman & Angsuran</span>
                </a>
                <div id="collapsefive" class="collapse" aria-labelledby="headingfive" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Pinjaman & Angsuran :</h6>
                        <a class="collapse-item" href="pinjaman/tb_pinjaman.php">Pinjaman</a>
                        <a class="collapse-item" href="pinjaman/tb_angsuran_pinjaman.php">Angsuran</a>
                        <a class="collapse-item" href="pinjaman/tb_tgpinjaman.php">Tagihan Pinjaman</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                User
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="user/log.php">
                    <span>LOG</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="../index.php">
                    <span><b>LAPORAN 2025</b></span>
                </a>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <h3><b>KUD BONDO SEPOLO 2021 - 2024</b></h3>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid"></div>





                <section id="profil-koperasi" class="col-md-10 mx-auto">
                    <h2><b>Profil Koperasi</b>
                        <div class="float-right mr-3 mb-20">
                            <a href="edit/profil.php" class="btn btn-danger">Edit Data</a>
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
                    <h2><b>Daftar Pengurus</b></h2>
                    <table>
                        <tbody>
                            <?php

                            if (mysqli_num_rows($pengurus) > 0) {
                                while ($data = mysqli_fetch_array($pengurus)) { ?>
                                    <tr>

                                        <td style="vertical-align: middle; width: 300px;"> <b> <?= $data['label'] ?> </b> </td>
                                        <td style="vertical-align: middle; width: 10px;"><?= $data['pemisah'] ?></td>
                                        <td style="vertical-align: middle;"><?= $data['isi'] ?></td>
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
                    <h2><b>Daftar Karyawan</b></h2>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 20px;">No.</th>
                                <th style="width: 400px;">Nama</th>
                                <th style="width: 300px;">Jabatan</th>
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





            </div>

            <footer>
                <p>&copy; 2024 KUD BONDO SEPOLO</p>
            </footer>

        </div>
    </div>
    <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="auth/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../_assets/vendor/jquery/jquery.min.js"></script>
    <script src="../_assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../_assets/js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

    <script>
        function refreshPage() {
            location.reload();
        }
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>

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