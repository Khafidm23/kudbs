<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../config/config.php';

if (isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Validasi nama tabel
    $allowedTables = ['tb_tgpinjaman'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query tgpinjaman
    $query_tgpinjaman = "
    SELECT nama, no_hp, pinjaman, angsuran, sisa_pinjaman, keterangan
    FROM `$table` 
    ORDER BY nama ASC";

    $result_tgpinjaman = mysqli_query($conn, $query_tgpinjaman);

    if (!$result_tgpinjaman) {
        die("Error in Tagihan_Pinjaman query: " . mysqli_error($conn));
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk daftar tgpinjaman
    $sheet->setCellValue('A1', 'Nama');
    $sheet->setCellValue('B1', 'No. HP');
    $sheet->setCellValue('C1', 'Pinjaman');
    $sheet->setCellValue('D1', 'Angsuran');
    $sheet->setCellValue('E1', 'Sisa Pinjaman');
    $sheet->setCellValue('F1', 'Keterangan');

    // Mengisi data tgpinjaman
    $row = 2;
    if (mysqli_num_rows($result_tgpinjaman) > 0) {
        while ($data_tgpinjaman = mysqli_fetch_assoc($result_tgpinjaman)) {
            $sheet->setCellValue('A' . $row, $data_tgpinjaman['nama']);
            $sheet->setCellValue('B' . $row, $data_tgpinjaman['no_hp']);
            $sheet->setCellValue('C' . $row, $data_tgpinjaman['pinjaman']);
            $sheet->setCellValue('D' . $row, $data_tgpinjaman['angsuran']);
            $sheet->setCellValue('E' . $row, $data_tgpinjaman['sisa_pinjaman']);
            $sheet->setCellValue('F' . $row, $data_tgpinjaman['keterangan']);
            $row++;
        }
    }

    // Bersihkan buffer sebelum mengirim header
    ob_end_clean();

    // Set header untuk file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Daftar_Tagihan_Pinjaman.xlsx"');
    header('Cache-Control: max-age=0');

    // Simpan file Excel ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit(); // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

mysqli_close($conn);
?>
