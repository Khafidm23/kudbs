<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../config/config.php';

if (isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Validasi nama tabel
    $allowedTables = ['rekap_gaji'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query gaji
    $query_gaji = "
    SELECT tanggal, nama, hm_awal, hm_akhir, jumlah_hm, fee_hm, total_fee, gaji_pokok, tunjangan, total_gaji, keterangan
    FROM `$table` 
    ORDER BY tanggal DESC";

    $result_gaji = mysqli_query($conn, $query_gaji);

    if (!$result_gaji) {
        die("Error in Rekap_Gaji query: " . mysqli_error($conn));
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk daftar gaji
    $sheet->setCellValue('A1', 'Tanggal');
    $sheet->setCellValue('B1', 'Nama');
    $sheet->setCellValue('C1', 'HM Awal');
    $sheet->setCellValue('D1', 'HM Akhir');
    $sheet->setCellValue('E1', 'Jumlah HM');
    $sheet->setCellValue('F1', 'Fee HM');
    $sheet->setCellValue('G1', 'Total Fee');
    $sheet->setCellValue('H1', 'Gaji Pokok');
    $sheet->setCellValue('I1', 'Tunjangan');
    $sheet->setCellValue('J1', 'Total Gaji');
    $sheet->setCellValue('K1', 'Keterangan');

    // Mengisi data gaji
    $row = 2;
    if (mysqli_num_rows($result_gaji) > 0) {
        while ($data_gaji = mysqli_fetch_assoc($result_gaji)) {
            $sheet->setCellValue('A' . $row, $data_gaji['tanggal']);
            $sheet->setCellValue('B' . $row, $data_gaji['nama']);
            $sheet->setCellValue('C' . $row, $data_gaji['hm_awal']);
            $sheet->setCellValue('D' . $row, $data_gaji['hm_akhir']);
            $sheet->setCellValue('E' . $row, $data_gaji['jumlah_hm']);
            $sheet->setCellValue('F' . $row, $data_gaji['fee_hm']);
            $sheet->setCellValue('G' . $row, $data_gaji['total_fee']);
            $sheet->setCellValue('H' . $row, $data_gaji['gaji_pokok']);
            $sheet->setCellValue('I' . $row, $data_gaji['tunjangan']);
            $sheet->setCellValue('J' . $row, $data_gaji['total_gaji']);
            $sheet->setCellValue('K' . $row, $data_gaji['keterangan']);
            $row++;
        }
    }

    // Bersihkan buffer sebelum mengirim header
    ob_end_clean();

    // Set header untuk file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Daftar_Gaji.xlsx"');
    header('Cache-Control: max-age=0');

    // Simpan file Excel ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit(); // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

mysqli_close($conn);
?>
