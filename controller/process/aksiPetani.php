<?php
header("Content-Type: application/json");
include '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_kecamatan = isset($_POST['id_kecamatan']) ? htmlspecialchars($_POST['id_kecamatan']) : '';


switch ($action) {
    case 'read':
        readPetani($conn, $id_kecamatan);
        break;
    case 'detail':
        detailPetani($conn);
        break;
    case 'create':
        createPetani($conn);
        break;
    case 'update':
        updatePetani($conn);
        break;
    case 'delete':
        deletePetani($conn);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}




// === Fungsi Ambil Data ===

function readPetani($conn, $id_kecamatan){
    if (!$id_kecamatan) {
       $q = "SELECT p.*,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_petani p
       LEFT JOIN tbl_desa d ON p.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       ";
        $stmt = $conn->prepare($q);
    }else{
        $q = "SELECT p.*,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_petani p
       LEFT JOIN tbl_desa d ON p.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
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


function readPetani2($conn, $id_kecamatan){
    if (!$id_kecamatan) {
       $q = "SELECT o.*,
            k.nama_kecamatan
       FROM tbl_petani o
       LEFT JOIN tbl_kecamatan k ON o.id_kecamatan = k.id_kecamatan
       ";
        $stmt = $conn->prepare($q);
    }else{
         $q = "SELECT o.*,
             k.nama_kecamatan
         FROM tbl_petani o 
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
function detailPetani($conn){
    $id_petani = $_POST['id_petani'];

    if (!isset($id_petani) || $id_petani === "") {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

   $q = "SELECT p.*,
             k.nama_kecamatan,
             k.id_kecamatan,
             d.id_desa,
             u.username
         FROM tbl_petani p
         LEFT JOIN tbl_desa d ON p.id_desa = d.id_desa
         LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
         LEFT JOIN tbl_users u ON p.id_petani = u.id_user
         WHERE p.id_petani = ?";

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
function createPetani($conn){


    try {
        // =====================
        // Ambil data POST
        // =====================
       
        $id_desa                = $_POST['id_desa'];
        $nama_petani            = $_POST['nama_petani'];
        $nik                    = $_POST['nik'];
        $kontak                 = $_POST['kontak'];
        $jekel                  = $_POST['jekel'];
        $username               = trim($_POST['username'] ?? '');
        $password               = trim($_POST['password'] ?? 'SIBIBIT');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // =====================
        // BEGIN TRANSACTION
        // =====================
        $conn->begin_transaction();


    $cek = $conn->prepare("SELECT COUNT(*) FROM tbl_users WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $cek->bind_result($count);
    $cek->fetch();
    $cek->close();

    if ($count > 0) {
        echo json_encode(["error" => "Username sudah digunakan, gunakan username lain."]);
       }

        // =====================
        // 1. Insert User
        // =====================
        $q1 = $conn->prepare("INSERT INTO tbl_users (username, password) VALUES (?, ?)");
        $q1->bind_param("ss", $username, $hashedPassword);

        if (!$q1->execute()) {
            throw new Exception("Gagal insert tbl_users");
        }

        $id_petani = $conn->insert_id;

        

        // =====================
        // 1. Insert Petani
        // =====================
        $q2 = $conn->prepare("INSERT INTO tbl_petani (id_petani, id_desa, nama_petani, jekel, kontak, nik) VALUES (?, ?, ?, ?, ?, ?)");
        $q2->bind_param("iissss", $id_petani,  $id_desa, $nama_petani, $jekel, $kontak, $nik);

        if (!$q2->execute()) {
            throw new Exception("Gagal insert tbl_petani");
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
function updatePetani($conn){
    header('Content-Type: application/json');

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_petani              = $_POST['id_petani'];
        $id_desa                = $_POST['id_desa'];
        $nama_petani            = $_POST['nama_petani'];
        $nik                    = $_POST['nik'];
        $kontak                 = $_POST['kontak'];
        $jekel                  = $_POST['jekel'];
        $username               = trim($_POST['username'] ?? '');
        $password               = trim($_POST['password'] ?? '');
       

        // =====================
        // BEGIN TRANSACTION
        // =====================
        $conn->begin_transaction();


    $cek = $conn->prepare("SELECT COUNT(*) FROM tbl_users WHERE username = ? AND id_user != ?");
    $cek->bind_param("si", $username, $id_petani);
    $cek->execute();
    $cek->bind_result($count);
    $cek->fetch();
    $cek->close();

    if ($count > 0) {
        echo json_encode(["error" => "Username sudah digunakan, gunakan username lain."]);
        return;
    }

    // --- Update data user
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = $conn->prepare("
            UPDATE tbl_users SET username = ?, password = ? WHERE id_user = ?
        ");
        $sql->bind_param("ssi", $username, $hashedPassword, $id_petani);
    } else {
        $sql = $conn->prepare("
            UPDATE tbl_users SET username = ? WHERE id_user = ?
        ");
        $sql->bind_param("si", $username, $id_petani);
    }

    $successUser = $sql->execute();
    $sql->close();

        // =====================
        // 1. UPDATE tbl_petani
        // =====================
        $q1 = $conn->prepare("UPDATE tbl_petani SET id_desa = ?, nama_petani = ?, jekel = ?, kontak = ?, nik = ? WHERE id_petani = ?");
        $q1->bind_param(
            "issssi",
            $id_desa,
            $nama_petani,
            $jekel,
            $kontak,
            $nik,
            $id_petani
        );

        if (!$q1->execute()) {
            throw new Exception("Gagal update tbl_petani");
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
function deletePetani($conn)
{
    if (!isset($_POST['id_petani'])) {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }
    $id_petani = $_POST['id_petani'];
    

    $sql = $conn->prepare("DELETE FROM tbl_users WHERE id_user = ?");
    $sql->bind_param("i", $id_petani);

    if ($sql->execute()) {
        echo json_encode(["message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus data: " . $conn->error]);
    }

    $sql->close();
}

$conn->close();
