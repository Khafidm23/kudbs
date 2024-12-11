<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../config/config.php';

if (isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Validasi nama tabel
    $allowedTables = ['tb_pinjaman'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query pinjaman
    $query_pinjaman = "
    SELECT tanggal, nama, no_hp, pinjaman, keterangan
    FROM `$table` 
    ORDER BY tanggal DESC";

    $result_pinjaman = mysqli_query($conn, $query_pinjaman);

    if (!$result_pinjaman) {
        die("Error in Pinjaman query: " . mysqli_error($conn));
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk daftar pinjaman
    $sheet->setCellValue('A1', 'Tanggal');
    $sheet->setCellValue('B1', 'Nama');
    $sheet->setCellValue('C1', 'No. HP');
    $sheet->setCellValue('D1', 'Jumlah Pinjaman');
    $sheet->setCellValue('E1', 'Keterangan');

    // Mengisi data pinjaman
    $row = 2;
    if (mysqli_num_rows($result_pinjaman) > 0) {
        while ($data_pinjaman = mysqli_fetch_assoc($result_pinjaman)) {
            $sheet->setCellValue('A' . $row, $data_pinjaman['tanggal']);
            $sheet->setCellValue('B' . $row, $data_pinjaman['nama']);
            $sheet->setCellValue('C' . $row, $data_pinjaman['no_hp']);
            $sheet->setCellValue('D' . $row, $data_pinjaman['pinjaman']);
            $sheet->setCellValue('E' . $row, $data_pinjaman['keterangan']);
            $row++;
        }
    }

    // Bersihkan buffer sebelum mengirim header
    ob_end_clean();

    // Set header untuk file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Daftar_Pinjaman.xlsx"');
    header('Cache-Control: max-age=0');

    // Simpan file Excel ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit(); // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

mysqli_close($conn);
?>
