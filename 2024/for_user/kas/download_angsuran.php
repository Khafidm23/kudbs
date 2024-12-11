<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../../config/config.php';

if (isset($_GET['table'])) {
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Validasi nama tabel
    $allowedTables = ['tb_angsuran'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query angsuran
    $query_angsuran = "
    SELECT tanggal, pengguna, no_hp, sumber, harga_hm, angsuran, keterangan
    FROM `$table` 
    ORDER BY tanggal DESC";

    $result_angsuran = mysqli_query($conn, $query_angsuran);

    if (!$result_angsuran) {
        die("Error in Angsuran query: " . mysqli_error($conn));
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk daftar angsuran
    $sheet->setCellValue('A1', 'Tanggal');
    $sheet->setCellValue('B1', 'Pengguna');
    $sheet->setCellValue('C1', 'ID');
    $sheet->setCellValue('D1', 'Sumber');
    $sheet->setCellValue('E1', 'Harga HM');
    $sheet->setCellValue('F1', 'Jumlah Angsuran');
    $sheet->setCellValue('G1', 'Keterangan');

    // Mengisi data angsuran
    $row = 2;
    if (mysqli_num_rows($result_angsuran) > 0) {
        while ($data_angsuran = mysqli_fetch_assoc($result_angsuran)) {
            $sheet->setCellValue('A' . $row, $data_angsuran['tanggal']);
            $sheet->setCellValue('B' . $row, $data_angsuran['pengguna']);
            $sheet->setCellValue('C' . $row, $data_angsuran['no_hp']);
            $sheet->setCellValue('D' . $row, $data_angsuran['sumber']);
            $sheet->setCellValue('E' . $row, $data_angsuran['harga_hm']);
            $sheet->setCellValue('F' . $row, $data_angsuran['angsuran']);
            $sheet->setCellValue('G' . $row, $data_angsuran['keterangan']);
            $row++;
        }
    }

    // Bersihkan buffer sebelum mengirim header
    ob_end_clean();

    // Set header untuk file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Daftar_Angsuran.xlsx"');
    header('Cache-Control: max-age=0');

    // Simpan file Excel ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit(); // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

mysqli_close($conn);
?>
