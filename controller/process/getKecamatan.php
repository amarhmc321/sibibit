<?php
header("Content-Type: application/json");
include '../config/database.php';
$query = "SELECT * FROM tbl_kecamatan order by id_kecamatan desc";
$result = $conn->query($query);
$data_kecamatan = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_kecamatan[] = [
            'id_kecamatan' => $row['id_kecamatan'],
            'nama_kecamatan'    => $row['nama_kecamatan']
        ];
    }
}

echo json_encode($data_kecamatan);
exit;
