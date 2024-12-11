<?php
include '../config/config.php';

$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
$search_condition = $search ? "AND pengguna LIKE '%$search%'" : "";

$query = "
    SELECT pengguna, 'tb_tgbs01' AS sumber, jumlah_hm, no_hp, harga_hm 
    FROM tb_tgbs01 
    WHERE sisa_tagihan > 0 $search_condition
    UNION
    SELECT pengguna, 'tb_tgbs02' AS sumber, jumlah_hm, no_hp, harga_hm 
    FROM tb_tgbs02 
    WHERE sisa_tagihan > 0 $search_condition
    UNION
    SELECT pengguna, 'tb_tgbs03' AS sumber, jumlah_hm, no_hp, harga_hm 
    FROM tb_tgbs03 
    WHERE sisa_tagihan > 0 $search_condition
    UNION
    SELECT pengguna, 'tb_tgbs04' AS sumber, jumlah_hm, no_hp, harga_hm 
    FROM tb_tgbs04 
    WHERE sisa_tagihan > 0 $search_condition
    UNION
    SELECT pengguna, 'tb_tgbs05' AS sumber, jumlah_hm, no_hp, harga_hm 
    FROM tb_tgbs05 
    WHERE sisa_tagihan > 0 $search_condition
";

$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>

