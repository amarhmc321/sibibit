<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_kecamatan = isset($_POST['id_kecamatan']) ? htmlspecialchars($_POST['id_kecamatan']) : '';


switch ($action) {
    case 'read2':
        readLahanUser($conn);
        break;
    case 'read':
        readLahan($conn, $id_kecamatan);
        break;
    case 'detail':
        detailLahan($conn);
        break;
    case 'create':
        createLahan($conn);
        break;
    case 'update':
        updateLahan($conn);
        break;
    case 'delete':
        deleteLahan($conn);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}




// === Fungsi Ambil Data ===

function readLahanUser($conn){
    $id_petani = $_SESSION['id_user'];
   
       $q = "SELECT l.*,
            p.nama_petani,
            d.nama_desa,
            k.nama_kecamatan,
            g.nama_kelompok
       FROM tbl_lahan l
       LEFT JOIN tbl_desa d ON l.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_petani p ON l.id_petani = p.id_petani
       LEFT JOIN tbl_groups g ON l.id_group = g.id_group
        WHERE l.id_petani = ?";

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


function readLahan($conn, $id_kecamatan){
    if (!$id_kecamatan) {
       $q = "SELECT l.*,
            p.nama_petani,
            d.nama_desa,
            k.nama_kecamatan,
            g.nama_kelompok
       FROM tbl_lahan l
       LEFT JOIN tbl_desa d ON l.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_petani p ON l.id_petani = p.id_petani
       LEFT JOIN tbl_groups g ON l.id_group = g.id_group
       ";
        $stmt = $conn->prepare($q);
    }else{
        $q = "SELECT p.*,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_petani p
       LEFT JOIN tbl_desa d ON p.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_lahan l ON d.id_desa = l.id_desa
       LEFT JOIN tbl_groups g ON l.id_group = g.id_group
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
function detailLahan($conn){
    $id_petani = $_POST['id_lahan'];

    if (!isset($id_petani) || $id_petani === "") {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

   $q = "SELECT l.*,
            k.id_kecamatan,
            g.nama_kelompok,
            p.nama_petani
       FROM tbl_lahan l
       LEFT JOIN tbl_desa d ON l.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_petani p ON l.id_petani = p.id_petani
       LEFT JOIN tbl_groups g ON l.id_group = g.id_group
    WHERE l.id_lahan = ?";

    $stmt = $conn->prepare($q);

    if (!$stmt) {
        echo json_encode(["error" => "Gagal mempersiapkan query: " . $conn->error]);
        return;
    }

    $stmt->bind_param("i", $id_petani);
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
function createLahan($conn)
{
    header('Content-Type: application/json');

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_desa              = $_POST['id_desa'];
        $id_group             = $_POST['id_group']?? null;
        $id_petani            = $_POST['id_petani'];
        $longitude            = $_POST['longitude'];
        $latitude             = $_POST['latitude'];
        $luas_lahan           = $_POST['luas_lahan'];
        
       

        // =====================
        // BEGIN TRANSACTION
        // =====================
        $conn->begin_transaction();

        // =====================
        // 1. Insert 
        // =====================
        $q1 = $conn->prepare("INSERT INTO tbl_lahan (id_desa, id_group, id_petani, longitude, latitude, luas_lahan) VALUES (?, ?, ?, ?, ?, ?)");
        $q1->bind_param("iiiddd", $id_desa, $id_group, $id_petani, $longitude, $latitude, $luas_lahan);

        if (!$q1->execute()) {
            throw new Exception("Gagal insert tbl_lahan");
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
function updateLahan($conn){
    header('Content-Type: application/json');

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_lahan             = $_POST['id_lahan'];
        $id_desa              = $_POST['id_desa'];
        $id_group             = $_POST['id_group']?? null;
        $id_petani            = $_POST['id_petani'];
        $longitude            = $_POST['longitude'];
        $latitude             = $_POST['latitude'];
        $luas_lahan           = $_POST['luas_lahan'];
       

        // =====================
        // BEGIN TRANSACTION
        // =====================
        $conn->begin_transaction();

        // =====================
        // 1. UPDATE tbl_petani
        // =====================
        $q1 = $conn->prepare("UPDATE tbl_lahan SET id_petani = ?, id_desa = ?, id_group = ?, longitude = ?, latitude = ?, luas_lahan = ? WHERE id_lahan = ?");
        $q1->bind_param(
            "iiidddi",
            $id_petani,
            $id_desa,
            $id_group,
            $longitude,
            $latitude,
            $luas_lahan,
            $id_lahan
        );

        if (!$q1->execute()) {
            throw new Exception("Gagal update tbl_lahan");
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
function deleteLahan($conn)
{
    if (!isset($_POST['id_lahan'])) {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }
    $id_lahan = $_POST['id_lahan'];
    $sql = $conn->prepare("DELETE FROM tbl_lahan WHERE id_lahan = ?");
    $sql->bind_param("i", $id_lahan);

    if ($sql->execute()) {
        echo json_encode(["message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus data: " . $conn->error]);
    }

    $sql->close();
}

$conn->close();
