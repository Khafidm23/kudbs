<?php
ob_start(); // Mulai buffer untuk mencegah output sebelum header

require '../../_assets/libs/vendor/autoload.php'; // Pastikan path ini sesuai dengan instalasi composer kamu

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../config/config.php';

if (isset($_GET['pengguna']) && isset($_GET['id']) && isset($_GET['table'])) {
    $pengguna = mysqli_real_escape_string($conn, $_GET['pengguna']);
    $table = mysqli_real_escape_string($conn, $_GET['table']);
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Validasi nama tabel
    $allowedTables = ['tb_tgbs01', 'tb_tgbs02', 'tb_tgbs03', 'tb_tgbs04', 'tb_tgbs05'];
    if (!in_array($table, $allowedTables)) {
        die("Invalid table name.");
    }

    // Query dinamis untuk HM
    $sources = ['tb_hmbs01', 'tb_hmbs02', 'tb_hmbs03', 'tb_hmbs04', 'tb_hmbs05'];
    $unionQueries = [];
    foreach ($sources as $source) {
        $unionQueries[] = "
        SELECT '$source' AS sumber, pengguna, no_hp, tanggal, jumlah_hm, harga_hm 
        FROM $source 
        WHERE pengguna = '$pengguna' 
        AND no_hp = (
            SELECT no_hp FROM $table WHERE id = '$id'
        )
        AND harga_hm = (
            SELECT harga_hm FROM $table WHERE id = '$id'
        )";
    }
    $query_hm = implode(' UNION ALL ', $unionQueries) . " ORDER BY tanggal ASC";
    $result_hm = mysqli_query($conn, $query_hm) or die("Error in HM query: " . mysqli_error($conn));

    // Query angsuran
    $query_angsuran = "
    SELECT pengguna, no_hp, tanggal, angsuran
    FROM tb_angsuran 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    AND harga_hm = (
        SELECT harga_hm FROM $table WHERE id = '$id'
    )
    ORDER BY tanggal ASC";
    $result_angsuran = mysqli_query($conn, $query_angsuran) or die("Error in Angsuran query: " . mysqli_error($conn));

    // Query tagihan
    $query_tagihan = "
    SELECT pengguna, no_hp, jumlah_hm, harga_hm, total_tagihan, total_angsuran, sisa_tagihan
    FROM $table 
    WHERE pengguna = '$pengguna' 
    AND no_hp = (
        SELECT no_hp FROM $table WHERE id = '$id'
    )
    AND harga_hm = (
        SELECT harga_hm FROM $table WHERE id = '$id'
    )
    ORDER BY pengguna ASC";

    
    $result_tagihan = mysqli_query($conn, $query_tagihan) or die("Error in Tagihan query: " . mysqli_error($conn));

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header untuk rincian HM
    $sheet->setCellValue('A1', 'Sumber');
    $sheet->setCellValue('B1', 'Nama');
    $sheet->setCellValue('C1', 'ID');
    $sheet->setCellValue('D1', 'Tanggal');
    $sheet->setCellValue('E1', 'Jumlah HM');
    $sheet->setCellValue('F1', 'Harga HM');

    // Mengisi data HM
    $row = 2;
    if (mysqli_num_rows($result_hm) > 0) {
        while ($data_hm = mysqli_fetch_assoc($result_hm)) {
            $sheet->setCellValue('A' . $row, $data_hm['sumber']);
            $sheet->setCellValue('B' . $row, $data_hm['pengguna']);
            $sheet->setCellValue('C' . $row, $data_hm['no_hp']);
            $sheet->setCellValue('D' . $row, $data_hm['tanggal']);
            $sheet->setCellValue('E' . $row, $data_hm['jumlah_hm']);
            $sheet->setCellValue('F' . $row, $data_hm['harga_hm']);
            $row++;
        }
    }

    // Menambahkan jarak antar tabel
    $row++;

    // Menulis header untuk rincian angsuran
    $sheet->setCellValue('A' . $row, 'Nama');
    $sheet->setCellValue('B' . $row, 'ID');
    $sheet->setCellValue('C' . $row, 'Tanggal');
    $sheet->setCellValue('D' . $row, 'Angsuran');

    // Mengisi data angsuran
    $row++;
    if (mysqli_num_rows($result_angsuran) > 0) {
        while ($data_angsuran = mysqli_fetch_assoc($result_angsuran)) {
            $sheet->setCellValue('A' . $row, $data_angsuran['pengguna']);
            $sheet->setCellValue('B' . $row, $data_angsuran['no_hp']);
            $sheet->setCellValue('C' . $row, $data_angsuran['tanggal']);
            $sheet->setCellValue('D' . $row, $data_angsuran['angsuran']);
            $row++;
        }
    }

    // Menambahkan jarak antar tabel
    $row++;

    // Menulis header untuk rincian tagihan
    $sheet->setCellValue('A' . $row, 'Nama');
    $sheet->setCellValue('B' . $row, 'ID');
    $sheet->setCellValue('C' . $row, 'Jumlah HM');
    $sheet->setCellValue('D' . $row, 'Harga HM');
    $sheet->setCellValue('E' . $row, 'Total Tagihan');
    $sheet->setCellValue('F' . $row, 'Total Angsuran');
    $sheet->setCellValue('G' . $row, 'Sisa Tagihan');

    // Mengisi data tagihan
    $row++;
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
header('Content-Disposition: attachment; filename="rincian_' . $pengguna . '.xlsx"');
header('Cache-Control: max-age=0');

// Simpan file Excel ke output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit(); // Tambahkan exit untuk menghentikan eksekusi lebih lanjut
}

mysqli_close($conn);
?>
