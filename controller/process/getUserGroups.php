<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';
$id_user   = $_SESSION['id_user'] ?? null; 
$query = "SELECT DISTINCT 
	g.id_group,
	g.nama_kelompok
  FROM tbl_lahan gd 
  LEFT JOIN tbl_groups g ON gd.id_group = g.id_group
  where gd.id_petani = $id_user";
$result = $conn->query($query);
$data_groups = [];


if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_groups[] = [
            'id_group' 		=> $row['id_group'],
            'nama_group'    => $row['nama_kelompok']
        ];
    }
}

echo json_encode($data_groups);
exit;
