<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';
$id_user = $_SESSION['id_user'] ?? null;

// Jika request meminta total luas lahan untuk id_group tertentu
if (isset($_GET['id_group'])) {
    $id_g = intval($_GET['id_group']);
    $st = $conn->prepare("SELECT COALESCE(SUM(luas_lahan), 0) AS tot_luas_lahan FROM tbl_lahan WHERE id_group = ?");
    $st->bind_param("i", $id_g);
    $st->execute();
    $r = $st->get_result()->fetch_assoc();
    echo json_encode(['tot_luas_lahan' => floatval($r['tot_luas_lahan'] ?? 0)]);
    exit;
}

$id_user = intval($id_user);
$query = "SELECT DISTINCT 
	g.id_group,
	g.nama_kelompok,
    COALESCE(tl.tot_luas_lahan, 0) AS tot_luas_lahan
  FROM tbl_lahan gd 
  LEFT JOIN tbl_groups g ON gd.id_group = g.id_group
  LEFT JOIN (
      SELECT id_group, SUM(luas_lahan) AS tot_luas_lahan
      FROM tbl_lahan
      GROUP BY id_group
  ) tl ON g.id_group = tl.id_group
  WHERE gd.id_petani = $id_user";
$result = $conn->query($query);
$data_groups = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_groups[] = [
            'id_group' 		=> $row['id_group'],
            'nama_group'    => $row['nama_kelompok'],
            'tot_luas_lahan'=> floatval($row['tot_luas_lahan'])
        ];
    }
}

echo json_encode($data_groups);
exit;
