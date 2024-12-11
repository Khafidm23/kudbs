<?php include '../_header.php'; ?>

<?php
require '../../_assets/libs/vendor/autoload.php'; // Pastikan path ini benar
use PhpOffice\PhpSpreadsheet\IOFactory;
use Ramsey\Uuid\Guid\Guid;

include '../config/config.php';

// Mengecek jika form di-submit
if (isset($_POST['upload'])) {
    // Mengambil nama tabel yang dipilih

    // Mengecek file yang di-upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file = $_FILES['file']['tmp_name'];

        try {
            // Membaca file Excel
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            // Loop untuk membaca setiap baris data dan memasukkan data ke database
            for ($row = 2; $row <= $highestRow; $row++) { // Mulai dari baris kedua untuk mengabaikan header
                // Menangkap data dari file excel
                $tanggal = $sheet->getCell('A' . $row)->getValue();
                $keterangan = $sheet->getCell('B' . $row)->getValue();
                $pengeluaran = $sheet->getCell('C' . $row)->getValue();

                // Mengabaikan baris jika kolom penting kosong (misalnya, pengguna)
                if (empty($tanggal) || empty($keterangan)) {
                    continue; // Skip baris ini jika pengguna atau no_hp kosong
                }

                // Generate UUID untuk setiap pengguna
                $uuid = Guid::uuid4()->toString();

                // Query untuk menyimpan data sesuai dengan tabel yang dipilih
                $query = "
                    INSERT INTO tb_pengeluaran (id, tanggal, Keterangan, Pengeluaran)
                    VALUES ('$uuid', '$tanggal', '$keterangan', '$pengeluaran')
                ";

                // Eksekusi query
                if (!mysqli_query($conn, $query)) {
                    die("Error: " . mysqli_error($conn));
                }
            }

            echo "<div class='alert alert-success'>Data berhasil diupload!</div>";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "No file selected or invalid file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload & Download Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            display: inline-block;
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-upload {
            background-color: #28a745;
        }


        .btn-upload:hover {
            background-color: #218838;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #aaa;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Upload & Download Template</h1>

        <form action="" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="file">Pilih File Excel:</label>
                <input type="file" name="file" id="file" accept=".xlsx, .xls" required>
            </div>

            <div class="buttons">
                <button type="submit" name="upload" class="btn-upload">Upload</button>

                <a href="download_template.php" class="btn btn-primary"> Download Template</a>
            </div>
        </form>
    </div>

    <footer>
        © 2024 Data Management System
    </footer>
</body>

</html>


<?php include '../_footer.php'; ?>