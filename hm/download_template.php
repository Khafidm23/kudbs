<?php
require '../_assets/libs/vendor/autoload.php'; // Pastikan path ini benar
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

try {
    // Definisi kolom sesuai dengan struktur tabel
    $columns = [
        'Tanggal',     // DATE
        'Pengguna',    // VARCHAR(255)
        'Nomor HP',    // VARCHAR(20)
        'HM Awal',     // INT
        'HM Akhir',    // INT
        'Jumlah HM',   // INT
        'Harga/HM',    // DECIMAL(10,2)
        'Keterangan'   // TEXT
    ];

    $sampleData = [
        ["'2025-01-01", 'User A', "'081234567890", 100, 150, 50, 375000, 'Pembayaran HM'],
        ["'2025-01-02", 'User B', "'082345678901", 200, 250, 50, 375000, 'Pembayaran HM'],
        ["'2025-01-03", 'User C', "'083456789012", 150, 180, 30, 375000, 'Pembayaran HM'],
        ['Sesuaikan Format Seperti Contoh Diatas Terutama Pada Penulisan Tanggal & Nomor HP, Pastikan Isi Setiap Kolom Dalam Bentuk Text Atau Angka Tidak Berupa Rumus Excel']
    ];

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menulis header
    if (is_array($columns) && !empty($columns)) {
        foreach ($columns as $index => $column) {
            // Konversi indeks angka ke huruf kolom
            $columnLetter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue($columnLetter . '1', $column); // Tulis header di baris pertama
        }
    } else {
        throw new Exception("Kolom tidak valid atau kosong.");
    }

    // Menambahkan data sampel ke dalam spreadsheet (mulai dari baris 2)
    $row = 2;
    foreach ($sampleData as $data) {
        $col = 1; // Mulai dari kolom pertama (A)
        foreach ($data as $value) {
            $columnLetter = Coordinate::stringFromColumnIndex($col);
            $sheet->setCellValue($columnLetter . $row, $value);
            $col++;
        }
        $row++;
    }

    // Header untuk download file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Template_Upload.xlsx"');
    header('Cache-Control: max-age=0');

    // Simpan file ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();

} catch (Exception $e) {
    // Jika terjadi error, tampilkan pesan
    echo "Error: " . $e->getMessage();
    exit();
}
?>
