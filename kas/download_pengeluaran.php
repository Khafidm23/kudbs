<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../config/config.php';

if (isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Validasi nama tabel
    $allowedTables = ['tb_pengeluaran'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query pengeluaran
    $query_pengeluaran = "
    SELECT tanggal, keterangan, pengeluaran
    FROM `$table` 
    ORDER BY tanggal DESC";

    $result_pengeluaran = mysqli_query($conn, $query_pengeluaran);

    if (!$result_pengeluaran) {
        die("Error in Pengeluaran query: " . mysqli_error($conn));
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk Daftar Pengeluaran
    $sheet->setCellValue('A1', 'Tanggal');
    $sheet->setCellValue('B1', 'Keterangan');
    $sheet->setCellValue('C1', 'Jumlah Pengeluaran');

    // Mengisi data pengeluaran
    $row = 2;
    if (mysqli_num_rows($result_pengeluaran) > 0) {
        while ($data_pengeluaran = mysqli_fetch_assoc($result_pengeluaran)) {
            $sheet->setCellValue('A' . $row, $data_pengeluaran['tanggal']);
            $sheet->setCellValue('B' . $row, $data_pengeluaran['keterangan']);
            $sheet->setCellValue('C' . $row, $data_pengeluaran['pengeluaran']);
            $row++;
        }
    }

    // Bersihkan buffer sebelum mengirim header
    ob_end_clean();

    // Set header untuk file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Daftar_Pengeluaran.xlsx"');
    header('Cache-Control: max-age=0');

    // Simpan file Excel ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit(); // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

mysqli_close($conn);
?>
