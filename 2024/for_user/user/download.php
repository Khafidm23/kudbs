<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../../config/config.php';

if (isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Validasi nama tabel
    $allowedTables = ['daftar_kontak'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query kontak
    $query_kontak = "
    SELECT nama, no_hp, sumber
    FROM `$table` 
    ORDER BY nama ASC";

    $result_kontak = mysqli_query($conn, $query_kontak);

    if (!$result_kontak) {
        die("Error in Rekap_Kontak query: " . mysqli_error($conn));
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk daftar kontak
    $sheet->setCellValue('A1', 'Nama');
    $sheet->setCellValue('B1', 'No. HP');
    $sheet->setCellValue('C1', 'Sumber');

    // Mengisi data kontak
    $row = 2;
    if (mysqli_num_rows($result_kontak) > 0) {
        while ($data_kontak = mysqli_fetch_assoc($result_kontak)) {
            $sheet->setCellValue('A' . $row, $data_kontak['nama']);
            $sheet->setCellValue('B' . $row, $data_kontak['no_hp']);
            $sheet->setCellValue('C' . $row, $data_kontak['sumber']);
            $row++;
        }
    }

    // Bersihkan buffer sebelum mengirim header
    ob_end_clean();

    // Set header untuk file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Daftar_Kontak.xlsx"');
    header('Cache-Control: max-age=0');

    // Simpan file Excel ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit(); // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

mysqli_close($conn);
?>
