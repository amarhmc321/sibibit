<?php
header("Content-Type: application/json");
include '../config/database.php';
$id_kecamatan= isset($_GET['id_kecamatan']) ? $_GET['id_kecamatan'] : '';
$query = "SELECT * FROM tbl_desa where id_kecamatan = $id_kecamatan";
$result = $conn->query($query);
$data_desa = [];


if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_desa[] = [
            'id_desa' => $row['id_desa'],
            'nama_desa'    => $row['nama_desa']
        ];
    }
}

echo json_encode($data_desa);
exit;
