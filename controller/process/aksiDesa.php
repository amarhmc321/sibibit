<?php
header("Content-Type: application/json");
include '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_kecamatan = isset($_POST['id_kecamatan']) ? htmlspecialchars($_POST['id_kecamatan']) : '';


switch ($action) {
    case 'read':
        readDesa($conn, $id_kecamatan);
        break;
    case 'detail':
        detailDesa($conn);
        break;
    case 'create':
        createDesa($conn);
        break;
    case 'update':
        updateDesa($conn);
        break;
    case 'delete':
        deleteDesa($conn);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}

// === Fungsi Ambil Data ===
function readDesa($conn, $id_kecamatan){
    if (!$id_kecamatan) {
       $q = "SELECT o.*,
            k.nama_kecamatan
       FROM tbl_desa o
       LEFT JOIN tbl_kecamatan k ON o.id_kecamatan = k.id_kecamatan
       ";
        $stmt = $conn->prepare($q);
    }else{
         $q = "SELECT o.*,
             k.nama_kecamatan
         FROM tbl_desa o 
         LEFT JOIN tbl_kecamatan k ON o.id_kecamatan = k.id_kecamatan
         WHERE o.id_kecamatan = ?";
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
function detailDesa($conn){
    $id_desa = $_POST['id_desa'];

    if (!isset($id_desa) || $id_desa === "") {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

   $q = "SELECT o.*,
             k.nama_kecamatan
         FROM tbl_desa o
         LEFT JOIN tbl_kecamatan k ON o.id_kecamatan = k.id_kecamatan
         WHERE o.id_desa = ?";

    $stmt = $conn->prepare($q);

    if (!$stmt) {
        echo json_encode(["error" => "Gagal mempersiapkan query: " . $conn->error]);
        return;
    }

    $stmt->bind_param("i", $id_desa);
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
function createDesa($conn)
{
    header('Content-Type: application/json');

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_kecamatan        = !empty($_POST['id_kecamatan']) ? $_POST['id_kecamatan'] : null;
        $nama_desa          = $_POST['nama_desa'];
        

        // =====================
        // BEGIN TRANSACTION
        // =====================
        $conn->begin_transaction();

        // =====================
        // 1. Insert 
        // =====================
        $q1 = $conn->prepare("INSERT INTO tbl_desa (id_kecamatan, nama_desa) VALUES (?, ?)");
        $q1->bind_param("is", $id_kecamatan, $nama_desa);

        if (!$q1->execute()) {
            throw new Exception("Gagal insert tbl_desa");
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
function updateDesa($conn){
    header('Content-Type: application/json');

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_desa            = $_POST['id_desa'];
        $id_kecamatan       =  !empty($_POST['id_kecamatan']) ? $_POST['id_kecamatan'] : null;
        $nama_desa          = $_POST['nama_desa'];
        
       

        // =====================
        // BEGIN TRANSACTION
        // =====================
        $conn->begin_transaction();

        // =====================
        // 1. UPDATE tbl_desa
        // =====================
        $q1 = $conn->prepare("UPDATE tbl_desa SET id_kecamatan = ?, nama_desa = ? WHERE id_desa = ?");
        $q1->bind_param(
            "isi",
            $id_kecamatan,
            $nama_desa,
            $id_desa
        );

        if (!$q1->execute()) {
            throw new Exception("Gagal update tbl_desa");
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
function deleteDesa($conn)
{
    if (!isset($_POST['id_desa'])) {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }
    $id_desa = $_POST['id_desa'];
    $sql = $conn->prepare("DELETE FROM tbl_desa WHERE id_desa = ?");
    $sql->bind_param("i", $id_desa);

    if ($sql->execute()) {
        echo json_encode(["message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus data: " . $conn->error]);
    }

    $sql->close();
}

$conn->close();
