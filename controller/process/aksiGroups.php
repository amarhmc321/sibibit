<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_kecamatan = isset($_POST['id_kecamatan']) ? htmlspecialchars($_POST['id_kecamatan']) : '';


switch ($action) {
    case 'read2':
        readGroupsUser($conn);
        break;
    case 'read':
        readGroups($conn, $id_kecamatan);
        break;
    case 'detail':
        detailGroups($conn);
        break;
    case 'group-detail':
        groupDetails($conn);
        break;
    case 'group-header':
        groupHeader($conn);
        break;
    case 'create':
        createGroups($conn);
        break;
    case 'update':
        updateGroups($conn);
        break;
    case 'delete':
        deleteGroups($conn);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}




// === Fungsi Ambil Data ===


function groupDetails($conn){
    $id_group = $_POST['id_group'];
        $q = "SELECT l.*,
            p.nama_petani,
            p.nik,
            d.nama_desa,
            k.nama_kecamatan,
            g.nama_kelompok
       FROM tbl_lahan l
       LEFT JOIN tbl_desa d ON l.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_petani p ON l.id_petani = p.id_petani
       LEFT JOIN tbl_groups g ON l.id_group = g.id_group
        WHERE g.id_group = ?";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("i", $id_group);
        
        $stmt->execute();
        $stmt = $stmt->get_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $data = $stmt->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);

    if ($stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
}



function groupHeader($conn){
    $id_group = $_POST['id_group'];

    if (!isset($id_group) || $id_group === "") {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

  $q = "SELECT g.*,
            d.nama_desa,
            k.nama_kecamatan,
            p.nama_petani as ketua
       FROM tbl_groups g
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
      WHERE g.id_group = ?";

    $stmt = $conn->prepare($q);


    if (!$stmt) {
        echo json_encode(["error" => "Gagal mempersiapkan query: " . $conn->error]);
        return;
    }

    $stmt->bind_param("i", $id_group);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $row = $result->fetch_assoc(); // Ambil satu baris langsung
    echo json_encode($row); // Return sebagai object tunggal

    $stmt->close();
}


function readGroupsUser($conn){
    $id_petani = $_SESSION['id_user'];
        $q = "
SELECT
    g.*,
    d.nama_desa,
    k.nama_kecamatan,
    COUNT(DISTINCT l2.id_petani) AS total_anggota,
    p.nama_petani AS ketua
FROM tbl_groups g
LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
INNER JOIN tbl_lahan l1 ON g.id_group = l1.id_group AND l1.id_petani = ?
LEFT JOIN tbl_lahan l2 ON g.id_group = l2.id_group
GROUP BY g.id_group
";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("i", $id_petani);
        
       
        $stmt->execute();
        $stmt = $stmt->get_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $data = $stmt->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);

    if ($stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
}


function readGroups($conn, $id_kecamatan){
    if (!$id_kecamatan) {
       $q = "SELECT g.*,
            d.nama_desa,
            k.nama_kecamatan,
            COUNT(DISTINCT l.id_petani) AS total_anggota,
            SUM(luas_lahan) AS total_luas_lahan,
            p.nama_petani as ketua
       FROM tbl_groups g
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_lahan l ON g.id_group = l.id_group
       GROUP BY g.id_group
       ";
        $stmt = $conn->prepare($q);
    }else{
        $q = "SELECT g.*,
            d.nama_desa,
            k.nama_kecamatan,
            COUNT(DISTINCT l.id_petani) AS total_anggota,
            p.nama_petani as ketua
       FROM tbl_groups g
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_lahan l ON g.id_group = l.id_group
         WHERE k.id_kecamatan = ?";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("i", $id_kecamatan);
    }
        
       
        $stmt->execute();
        $stmt = $stmt->get_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $data = $stmt->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);

    if ($stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
}

// === Fungsi Ambil Data Detail/edit ===
function detailGroups($conn){
    $id_group = $_POST['id_group'];

    if (!isset($id_group) || $id_group === "") {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

   $q = "SELECT g.*,
             d.nama_desa,
             k.id_kecamatan,
             k.nama_kecamatan,
             p.nama_petani
         FROM tbl_groups g
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       
         WHERE g.id_group = ?";

    $stmt = $conn->prepare($q);

    if (!$stmt) {
        echo json_encode(["error" => "Gagal mempersiapkan query: " . $conn->error]);
        return;
    }

    $stmt->bind_param("i", $id_group);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $row = $result->fetch_assoc(); // Ambil satu baris langsung
    echo json_encode($row); // Return sebagai object tunggal

    $stmt->close();
}



// === Fungsi Tambah Data ===
function createGroups($conn)
{
    header('Content-Type: application/json');

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_desa        = $_POST['id_desa'];
        $nama_kelompok  = $_POST['nama_kelompok'];
        $id_leader      = $_POST['id_petani'] ?? null;
        $komoditas      = $_POST['komoditas'];
       

        // =====================
        // BEGIN TRANSACTION
        // =====================
        $conn->begin_transaction();

        // =====================
        // 1. Insert 
        // =====================
        $q1 = $conn->prepare("INSERT INTO tbl_groups (id_desa, nama_kelompok, id_leader, komoditas) VALUES (?, ?, ?, ?)");
        $q1->bind_param("isis", $id_desa, $nama_kelompok, $id_leader, $komoditas);

        if (!$q1->execute()) {
            throw new Exception("Gagal insert tbl_groups");
        }
       
        // =====================
        // COMMIT
        // =====================
        $conn->commit();

        echo json_encode([
            "status"  => "success",
            "message" => " berhasil ditambahkan"
        ]);

    } catch (Exception $e) {

        // =====================
        // ROLLBACK
        // =====================
        $conn->rollback();

        echo json_encode([
            "message" => $e->getMessage()
        ]);
    }
}


// === Fungsi Update Data ===
function updateGroups($conn){
    header('Content-Type: application/json');

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_group            = $_POST['id_group'];

        $id_desa        = $_POST['id_desa'];
        $nama_kelompok  = $_POST['nama_kelompok'];
        $id_leader      = $_POST['id_petani'] ?? null;
        $komoditas      = $_POST['komoditas'];
       

        // =====================
        // BEGIN TRANSACTION
        // =====================
        $conn->begin_transaction();

        // =====================
        // 1. UPDATE tbl_groups
        // =====================
        $q1 = $conn->prepare("UPDATE tbl_groups SET id_desa = ?, nama_kelompok = ?, id_leader = ?, komoditas = ? WHERE id_group = ?");
        $q1->bind_param(
            "isisi",
            $id_desa,
            $nama_kelompok,
            $id_leader,
            $komoditas,
            $id_group
        );

        if (!$q1->execute()) {
            throw new Exception("Gagal update tbl_groups");
        }

   

        // =====================
        // COMMIT
        // =====================
        $conn->commit();

        echo json_encode([
            "status"  => "success",
            "message" => "Data  berhasil diperbarui"
        ]);

    } catch (Exception $e) {

        // =====================
        // ROLLBACK
        // =====================
        $conn->rollback();

        echo json_encode([
            "status"  => "error",
            "message" => $e->getMessage()
        ]);
    }
}


// === Fungsi Hapus Data ===
function deleteGroups($conn)
{
    if (!isset($_POST['id_group'])) {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }
    $id_group = $_POST['id_group'];
    $sql = $conn->prepare("DELETE FROM tbl_groups WHERE id_group = ?");
    $sql->bind_param("i", $id_group);

    if ($sql->execute()) {
        echo json_encode(["message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus data: " . $conn->error]);
    }

    $sql->close();
}

$conn->close();
