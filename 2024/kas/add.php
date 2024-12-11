<?php include '../_header.php'; ?>

<?php
include '../config/config.php';
require "../../_assets/libs/vendor/autoload.php";

use Ramsey\Uuid\Uuid;

$notification = "";
$table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING); // Mendapatkan nama tabel dari URL

// Validasi nama tabel untuk mencegah SQL Injection
$allowed_tables = ['tb_angsuran']; // Daftar tabel yang diizinkan
if (!in_array($table, $allowed_tables)) {
    die("Tabel tidak valid.");
}

if (isset($_POST['bsimpan'])) {
    $errors = [];
    $data_count = count($_POST['tanggal']); // Hitung jumlah entri data dari array POST

    for ($i = 0; $i < $data_count; $i++) {
        $uuid = Uuid::uuid4()->toString();
        $tanggal = $_POST['tanggal'][$i];
        $pengguna = $_POST['pengguna'][$i];
        $no_hp = $_POST['no_hp'][$i];
        $sumber = $_POST['sumber'][$i];
        $harga_hm = $_POST['harga_hm'][$i];
        $angsuran = $_POST['angsuran'][$i];
        $keterangan = $_POST['ket'][$i];

        if (empty($tanggal) || empty($pengguna) || empty($angsuran)) {
            $errors[] = "Data pada baris ke-" . ($i + 1) . " tidak lengkap.";
        } else {
            $stmt = $conn->prepare("INSERT INTO $table (id, tanggal, pengguna, no_hp, sumber, harga_hm, angsuran, keterangan) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $uuid, $tanggal, $pengguna, $no_hp, $sumber, $harga_hm, $angsuran, $keterangan);

            if (!$stmt->execute()) {
                $errors[] = "Error pada baris ke-" . ($i + 1) . ": " . $stmt->error;
            }
        }
    }

    if (empty($errors)) {
        $notification = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
    } else {
        foreach ($errors as $error) {
            $notification .= "<div class='alert alert-danger'>$error</div>";
        }
    }
}

?>

<div class="col-md-10 mx-auto">
    <div class="card mt-3">
        <div class="card-header bg-info text-light d-flex justify-content-center align-items-center">
            Tambah Angsuran Pemakaian HM
        </div>
        <div class="card-body">
            <form action="" method="POST" onsubmit="return validateForm()">
                <div id="data-container">
                    <div class="data-entry">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Tanggal :</label>
                                <input name="tanggal[]" type="date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cari Pengguna:</label>
                                <input type="text" id="cariPengguna" class="form-control pengguna-search"
                                    placeholder="Masukkan nama pengguna">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih Pengguna:</label>
                                <select id="dropdownPengguna" name="pengguna[]" class="form-control pengguna-dropdown"
                                    required>
                                    <option value="">Pilih Pengguna</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Angsuran :</label>
                                <input name="angsuran[]" type="number" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan :</label>
                                <input name="ket[]" class="form-control" maxlength="100">
                            </div>
                        </div>
                        <input name="no_hp[]" type="hidden" class="form-control" required>
                        <input name="harga_hm[]" type="hidden" class="form-control" required>
                        <input name="sumber[]" type="hidden" class="form-control" required>
                        <hr>
                    </div>
                </div>
                <div class="text-center">
                    <hr>
                    <?php echo $notification; ?>
                    <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                    <button class="btn btn-danger" name="bkosongkan" type="reset">Kosongkan</button>
                    <a href="<?= $table ?>.php" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-info"></div>
    </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const dropdowns = document.querySelectorAll('.pengguna-dropdown');
    dropdowns.forEach(function(dropdown) {
        if (!dropdown.value) {
            alert('Silakan pilih pengguna dari daftar dropdown!');
            e.preventDefault();
            return false;
        }
    });
});


    document.getElementById('cariPengguna').addEventListener('input', function() {
    const searchValue = this.value;
    const dropdownElement = document.getElementById('dropdownPengguna');
    
    // Panggil fungsi fetchPengguna untuk memperbarui dropdown
    fetchPengguna(searchValue, dropdownElement);
});

function fetchPengguna(search, dropdownElement) {
    // Kirim AJAX request ke `fetch_pengguna.php`
    fetch(`fetch_pengguna.php?search=${encodeURIComponent(search)}`)
        .then(response => response.json())
        .then(data => {
            // Kosongkan dropdown terlebih dahulu
            dropdownElement.innerHTML = `<option value="">Pilih Pengguna</option>`;

            // Isi dropdown dengan data yang cocok
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.pengguna;
                option.textContent = `${item.pengguna} - ${item.sumber} (HM: ${item.jumlah_hm}, No. HP: ${item.no_hp})`;
                dropdownElement.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching pengguna:', error));
}

    // Fungsi untuk mengambil data pengguna berdasarkan input teks
    function fetchPengguna(searchValue, dropdownElement) {
        fetch(`fetch_pengguna.php?search=${encodeURIComponent(searchValue)}`)
            .then(response => response.json())
            .then(data => {
                dropdownElement.innerHTML = '<option value="">Pilih Pengguna</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.pengguna;
                    option.setAttribute('data-nomor', item.no_hp);
                    option.setAttribute('data-sumber', item.sumber);
                    option.setAttribute('data-harga', item.harga_hm);
                    option.textContent = `${item.pengguna} - ${item.sumber} (HM: ${item.jumlah_hm}, No. HP: ${item.no_hp})`;
                    dropdownElement.appendChild(option);
                });
            });
    }

    document.addEventListener('input', function (event) {
        if (event.target.classList.contains('pengguna-search')) {
            const searchValue = event.target.value; // Nilai input pencarian
            const dropdownElement = event.target.nextElementSibling; // Dropdown elemen terkait
            fetchPengguna(searchValue, dropdownElement); // Panggil fungsi meskipun kosong
        }
    });


    document.addEventListener('change', function (event) {
        if (event.target.classList.contains('pengguna-dropdown')) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const dataEntry = event.target.closest('.data-entry');
            const nomorInput = dataEntry.querySelector('input[name="no_hp[]"]');
            const hargaInput = dataEntry.querySelector('input[name="harga_hm[]"]');
            const sumberInput = dataEntry.querySelector('input[name="sumber[]"]');
            nomorInput.value = selectedOption.getAttribute('data-nomor');
            hargaInput.value = selectedOption.getAttribute('data-harga');
            sumberInput.value = selectedOption.getAttribute('data-sumber');
        }
    });

    function validateForm() {
        var isValid = true;
        var inputs = document.querySelectorAll('input[required]');
        inputs.forEach(function (input) {
            if (!input.value) {
                input.setCustomValidity('Harap isi bidang ini.');
                isValid = false;
            } else {
                input.setCustomValidity('');
            }
        });
        return isValid;
    }
</script>

<?php include '../_footer.php'; ?>