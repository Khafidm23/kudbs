<?php
function updateTagihanGabungan($conn) {
    // Hapus semua data di tagihan_gabungan
    $sql = "DELETE FROM tagihan_gabungan";
    $conn->query($sql);

    // Gabungkan dan hitung data dari semua tabel
    $sql = "INSERT INTO tagihan_gabungan (id, nama, no_hp, total_tagihan, total_angsuran, sisa_tagihan)
            SELECT UUID(), pengguna, no_hp, SUM(total_tagihan), SUM(total_angsuran), SUM(sisa_tagihan)
            FROM (
                SELECT pengguna, no_hp, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs01
                UNION ALL
                SELECT pengguna, no_hp, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs02
                UNION ALL
                SELECT pengguna, no_hp, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs03
                UNION ALL
                SELECT pengguna, no_hp, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs04
                UNION ALL
                SELECT pengguna, no_hp, total_tagihan, total_angsuran, sisa_tagihan FROM tb_tgbs05
            ) AS combined
            GROUP BY pengguna, no_hp";
    $conn->query($sql);
}
?>
