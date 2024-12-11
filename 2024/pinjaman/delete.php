<?php
include '../config/config.php';

$id = $_GET['id']; // Mendapatkan ID dari URL
$table = $_GET['table']; // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['tb_angsuran_pinjaman', 'tb_pinjaman']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    die("Tabel tidak valid.");
}

$sql = "DELETE FROM $table WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);

try {
if ($stmt->execute()) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    throw new Exception();
    }
} catch (Exception $e) {
    $_SESSION['message'] = ["type" => "error", "text" => "Data tidak bisa dihapus karena terdapat <b>Angsuran</b> dengan nama tersebut dalam table <b>Angsuran</b>. " ];
}

$conn->close();
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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../_assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="../index.php">
                    <span>Dashboard</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                Laporan Jasa Excavator
            </div>
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <span>Rekapan HM</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded" >
                        <h6 class="collapse-header">Rekapan HM:</h6>
                        <a class="collapse-item" href="../hm/tb_hmbs01.php">HM BS 01</a>
                        <a class="collapse-item" href="../hm/tb_hmbs02.php">HM BS 02</a>
                        <a class="collapse-item" href="../hm/tb_hmbs03.php">HM BS 03</a>
                        <a class="collapse-item" href="../hm/tb_hmbs04.php">HM BS 04</a>
                        <a class="collapse-item" href="../hm/tb_hmbs05.php">HM BS 05</a>                    
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
                        <a class="collapse-item" href="../tghn/tb_tgbs01.php">Tagihan BS 01</a>
                        <a class="collapse-item" href="../tghn/tb_tgbs02.php">Tagihan BS 02</a>
                        <a class="collapse-item" href="../tghn/tb_tgbs03.php">Tagihan BS 03</a>
                        <a class="collapse-item" href="../tghn/tb_tgbs04.php">Tagihan BS 04</a>
                        <a class="collapse-item" href="../tghn/tb_tgbs05.php">Tagihan BS 05</a>
                    </div>
                </div>
            </li>
            <!-- Nav Item - Pages Collapse Menu -->
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
                        <a class="collapse-item" href="../kas/tb_angsuran.php">Angsuran HM</a>
                        <a class="collapse-item" href="../kas/tb_pengeluaran.php">Pengeluaran</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                GAJI & PINJAMAN KARYAWAN
            </div>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#">
                    <span>Rekapan Gaji Karyawan</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsefour"
                    aria-expanded="true" aria-controls="collapsefour">
                    <span>Pinjaman & Angsuran</span>
                </a>
                <div id="collapsefour" class="collapse" aria-labelledby="headingfour" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Pinjaman & Angsuran :</h6>
                        <a class="collapse-item" href="tb_pinjaman.php">Pinjaman</a>
                        <a class="collapse-item" href="tb_angsuran_pinjaman.php">Angsuran</a>
                        <a class="collapse-item" href="tb_tgpinjaman.php">Tagihan Pinjaman</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                User
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="../user/tb_user.php">
                    <span>Managemen User</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
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
                    <h5><b>KUD BONDO SEPOLO</b></h5>
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
                <div class="container-fluid">

                <?php
                    if (isset($_SESSION['message'])) {
                        $messageType = $_SESSION['message']['type'];
                        $messageText = $_SESSION['message']['text'];
                        $alertClass = ($messageType == 'error') ? 'alert-danger' : 'alert-success';
                        echo "<div class='alert $alertClass' role='alert'>$messageText </div>";
                        unset($_SESSION['message']);
                    }
                    ?>
                <a href="<?php echo htmlspecialchars($table); ?>.php" class="btn btn-primary">Kembali</a>
                </div>
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
                    <a class="btn btn-primary" href="../auth/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../_assets/vendor/jquery/jquery.min.js"></script>
    <script src="../_assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../_assets/js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
    function refreshPage() {
    location.reload();
    }
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>