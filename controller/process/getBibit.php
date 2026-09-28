<?php
header("Content-Type: application/json");
include '../config/database.php';
$query = "SELECT * FROM tbl_bibit order by id_bibit desc";
$result = $conn->query($query);
$data_bibit = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_bibit[] = [
            'id_bibit' => $row['id_bibit'],
            'nama_bibit'    => $row['nama_bibit'],
            'jml_per_hektar' => intval($row['jml_per_hektar'] ?? 100),
            'satuan'        => $row['satuan'] ?? 'pohon'
        ];
    }
}

echo json_encode($data_bibit);
exit;
