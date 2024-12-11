<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../config/config.php';

if (isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Validasi nama tabel
    $allowedTables = ['tb_tgbs01', 'tb_tgbs02', 'tb_tgbs03', 'tb_tgbs04', 'tb_tgbs05'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query tagihan
    $query_tagihan = "
    SELECT pengguna, no_hp, jumlah_hm, harga_hm, total_tagihan, total_angsuran, sisa_tagihan
    FROM `$table` 
    ORDER BY pengguna ASC";

    $result_tagihan = mysqli_query($conn, $query_tagihan);

    if (!$result_tagihan) {
        die("Error in Tagihan query: " . mysqli_error($conn));
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk rincian tagihan
    $sheet->setCellValue('A1', 'Nama');
    $sheet->setCellValue('B1', 'No. HP');
    $sheet->setCellValue('C1', 'Jumlah HM');
    $sheet->setCellValue('D1', 'Harga HM');
    $sheet->setCellValue('E1', 'Total Tagihan');
    $sheet->setCellValue('F1', 'Total Angsuran');
    $sheet->setCellValue('G1', 'Sisa Tagihan');

    // Mengisi data tagihan
    $row = 2;
    if (mysqli_num_rows($result_tagihan) > 0) {
        while ($data_tagihan = mysqli_fetch_assoc($result_tagihan)) {
            $sheet->setCellValue('A' . $row, $data_tagihan['pengguna']);
            $sheet->setCellValue('B' . $row, $data_tagihan['no_hp']);
            $sheet->setCellValue('C' . $row, $data_tagihan['jumlah_hm']);
            $sheet->setCellValue('D' . $row, $data_tagihan['harga_hm']);
            $sheet->setCellValue('E' . $row, $data_tagihan['total_tagihan']);
            $sheet->setCellValue('F' . $row, $data_tagihan['total_angsuran']);
            $sheet->setCellValue('G' . $row, $data_tagihan['sisa_tagihan']);
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
