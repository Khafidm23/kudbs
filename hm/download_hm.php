<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../config/config.php';

if (isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Validasi nama tabel
    $allowedTables = ['tb_hmbs01', 'tb_hmbs02', 'tb_hmbs03', 'tb_hmbs04', 'tb_hmbs05'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query hm
    $query_hm = "
    SELECT tanggal, pengguna, no_hp, hm_awal, hm_akhir, jumlah_hm, harga_hm, keterangan
    FROM `$table` 
    ORDER BY tanggal DESC";

    $result_hm = mysqli_query($conn, $query_hm);

    if (!$result_hm) {
        die("Error in Rekap HM query: " . mysqli_error($conn));
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk rekapan hm
    $sheet->setCellValue('A1', 'Tanggal');
    $sheet->setCellValue('B1', 'Nama Pengguna');
    $sheet->setCellValue('C1', 'Nomor HP');
    $sheet->setCellValue('D1', 'HM Awal');
    $sheet->setCellValue('E1', 'HM Akhir');
    $sheet->setCellValue('F1', 'Jumlah HM');
    $sheet->setCellValue('G1', 'Harga/HM');
    $sheet->setCellValue('H1', 'Keterangan');

    // Mengisi data hm
    $row = 2;
    if (mysqli_num_rows($result_hm) > 0) {
        while ($data_hm = mysqli_fetch_assoc($result_hm)) {
            $sheet->setCellValue('A' . $row, $data_hm['tanggal']);
            $sheet->setCellValue('B' . $row, $data_hm['pengguna']);
            $sheet->setCellValue('C' . $row, $data_hm['no_hp']);
            $sheet->setCellValue('D' . $row, $data_hm['hm_awal']);
            $sheet->setCellValue('E' . $row, $data_hm['hm_akhir']);
            $sheet->setCellValue('F' . $row, $data_hm['jumlah_hm']);
            $sheet->setCellValue('G' . $row, $data_hm['harga_hm']);
            $sheet->setCellValue('H' . $row, $data_hm['keterangan']);
            $row++;
        }
    }

    // Bersihkan buffer sebelum mengirim header
    ob_end_clean();

    // Set header untuk file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Rekap_' . $table . '.xlsx"');
    header('Cache-Control: max-age=0');

    // Simpan file Excel ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit(); // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

mysqli_close($conn);
?>
