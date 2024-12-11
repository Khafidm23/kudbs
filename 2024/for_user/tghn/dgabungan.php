<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../../config/config.php';

if (isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Validasi nama tabel
    $allowedTables = ['tagihan_gabungan'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query tagihan
    $query_tagihan = "
    SELECT nama, no_hp, total_tagihan, total_angsuran, sisa_tagihan
    FROM `$table` 
    ORDER BY nama ASC";

    $result_tagihan = mysqli_query($conn, $query_tagihan);

    if (!$result_tagihan) {
        die("Error in Tagihan query: " . mysqli_error($conn));
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk rincian tagihan
    $sheet->setCellValue('A1', 'Nama');
    $sheet->setCellValue('B1', 'ID');
    $sheet->setCellValue('C1', 'Total Tagihan');
    $sheet->setCellValue('D1', 'Total Angsuran');
    $sheet->setCellValue('E1', 'Sisa Tagihan');

    // Mengisi data tagihan
    $row = 2;
    if (mysqli_num_rows($result_tagihan) > 0) {
        while ($data_tagihan = mysqli_fetch_assoc($result_tagihan)) {
            $sheet->setCellValue('A' . $row, $data_tagihan['nama']);
            $sheet->setCellValue('B' . $row, $data_tagihan['no_hp']);
            $sheet->setCellValue('C' . $row, $data_tagihan['total_tagihan']);
            $sheet->setCellValue('D' . $row, $data_tagihan['total_angsuran']);
            $sheet->setCellValue('E' . $row, $data_tagihan['sisa_tagihan']);
            $row++;
        }
    }

    // Bersihkan buffer sebelum mengirim header
    ob_end_clean();

    // Set header untuk file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="rincian_' . $table . '.xlsx"');
    header('Cache-Control: max-age=0');

    // Simpan file Excel ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit(); // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

mysqli_close($conn);
?>
