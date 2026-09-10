<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';
require_once 'helper.php';
date_default_timezone_set('Asia/Makassar');

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_user = isset($_POST['id_user']) ? $_POST['id_user'] : '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../../index.php");
        exit;
    }

switch ($action) {
    case 'profile':
        updateProfile($conn);
        break;
    case 'status':
        updateStatus($conn);
        break;
    case 'login':
        loginUsers($conn);
        break;
    case 'logout':
        logoutUsers($conn);
        break;
    case 'read':
        readUsers($conn);
        break;
    case 'detail':
        detailUsers($conn, $id_user);
        break;
    case 'create':
        createUsers($conn);
        break;
    case 'update':
        updateUsers($conn);
        break;
    case 'delete':
        deleteUsers($conn,  $id_user);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}



function loginUsers($conn){
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $record   = date('Y-m-d H:i:s');

    if (empty($username) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Silakan isi semua kolom."]);
        return;
    }

    // Cek berdasarkan username saja
    $sql = " SELECT u.*,
            p.nama_petani
    FROM tbl_users u
    LEFT JOIN tbl_petani p ON u.id_user = p.id_petani 
    WHERE username = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Kesalahan server saat login."]);
        return;
    }


    $result = $stmt->get_result();
    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();


        if (password_verify($password, $user['password'])) {

            if ($user['s_aktif'] == 2) {
                echo json_encode(["success" => false, "message" => "Akun anda telah di nonaktifkan."]);
                return;
            }
            // Update waktu login
            $update = $conn->prepare("UPDATE tbl_users SET record = ? WHERE id_user = ?");
            $update->bind_param("si", $record, $user['id_user']);
            $update->execute();

            if (session_status() === PHP_SESSION_NONE) session_start();

            $_SESSION['id_user']        = $user['id_user'];
            $_SESSION['nama']           = $user['nama_petani'] ?? $user['username'];
            $_SESSION['username']       = $user['username'];
            $_SESSION['level']          = $user['level'];
            $_SESSION['foto']           = $user['foto'] ?? 'default.png';


            echo json_encode([
                "success" => true,
                "message" => "Login berhasil!",
                "level"   => $user['level'] 
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Password salah."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "username tidak ditemukan."
        ]);
    }

    $stmt->close();
}





function updateStatus($conn){
$record   = date('Y-m-d H:i:s');
$id_user   = $_SESSION['id_user'] ?? null; 
    $update = $conn->prepare("UPDATE tbl_users SET record = ? WHERE id_user = ?");
    $update->bind_param("si", $record, $id_user);
    $update->execute();
}

function updateProfile($conn){

    $id_user       = $_SESSION['id_user'];
    $username      = trim($_POST['username'] ?? '');
    $password      = trim($_POST['password'] ?? '');

    // --- Ambil foto lama
    $stmtOld = $conn->prepare("SELECT foto, nama FROM tbl_users WHERE id_user = ?");
    $stmtOld->bind_param("i", $id_penulis);
    $stmtOld->execute();
    $resultOld = $stmtOld->get_result()->fetch_assoc();
    $oldFoto   = $resultOld['foto'] ?? "default.png";
    $namaKaryawan = $resultOld['nama'] ?? "user";
    $stmtOld->close();

    // simpan ke folder karyawan
    $foto = handleUploadFoto($_FILES, 'foto', $namaKaryawan, $oldFoto, "../../img/karyawan/");
   
    // --- Validasi username
    if (empty($username)) {
        echo json_encode(["error" => "username wajib diisi."]);
        return;
    }

    $cek = $conn->prepare("SELECT COUNT(*) FROM tbl_users WHERE username = ? AND id_user != ?");
    $cek->bind_param("si", $username, $id_user);
    $cek->execute();
    $cek->bind_result($count);
    $cek->fetch();
    $cek->close();

    if ($count > 0) {
        echo json_encode(["error" => "username sudah digunakan, gunakan username lain."]);
        return;
    }

    // --- Update data user
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = $conn->prepare(" UPDATE tbl_users SET username = ?, password = ?, foto = ?  WHERE id_user = ?");
        $sql->bind_param("sssi",  $username, $hashedPassword, $foto, $id_user);
    } else {
        $sql = $conn->prepare("UPDATE tbl_users SET username = ?, foto = ? WHERE id_user = ?");
        $sql->bind_param("ssi",  $username, $foto, $id_user);
    }

    $successUser = $sql->execute();
    $sql->close();


    if ($successUser) {
        $_SESSION['username']     = $username;
        $_SESSION['foto']         = $foto;
        echo json_encode(["success" => true, "message" => "Profil berhasil diperbarui"]);
    }else {
        echo json_encode(["error" => "Gagal update data penulis."]);
    }
}









function logoutUsers($conn)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
        exit;
    }

    // Cek apakah sesi ada
    if (!isset($_SESSION['id_user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Belum login']);
        exit;
    }

    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $logout_time = date('Y-m-d H:i:s');

        // Update kolom logout di tabel admin
        $stmt = $conn->prepare("UPDATE tbl_users SET logout = ? WHERE id_user = ?");
        if ($stmt) {
            $stmt->bind_param("si", $logout_time, $id_user);
            $stmt->execute();
            $stmt->close();
        }
    }

    session_destroy();

    echo json_encode([
        "status" => "success",
        "message" => "Logout berhasil dan waktu logout dicatat"
    ]);
}


// === Fungsi Ambil Data ===
function readUsers($conn){

    $level     = $_SESSION['level']     ?? null;
    $sql = "
        SELECT * FROM tbl_users";

   
    $stmt = $conn->prepare($sql);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Gagal eksekusi query: " . $stmt->error]);
        return;
    }

    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}




// === Fungsi Ambil Data Detail/edit ===
function detailUsers($conn, $id_user){
   
    $sql = "
        SELECT * FROM tbl_users WHERE id_user = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["error" => "Prepare failed: " . $conn->error]);
        return;
    }

    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $row = $result->fetch_assoc();
    echo json_encode($row);
}



// === Fungsi Tambah Data ===
function createUsers($conn){
    $username     = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password     = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $level        = filter_input(INPUT_POST, 'level', FILTER_VALIDATE_INT);
    $nama         = $_POST['nama'];
   

    if (empty($username) || empty($password) || empty($level) || empty($nama)) {
        echo json_encode(["error" => "Semua kolom wajib diisi."]);
        return;
    }

    // ✅ Cek apakah username sudah ada
    $cek_username = $conn->prepare("SELECT COUNT(*) FROM tbl_users WHERE username = ?");
    $cek_username->bind_param("s", $username);
    $cek_username->execute();
    $cek_username->bind_result($count_username);
    $cek_username->fetch();
    $cek_username->close();

    if ($count_username > 0) {
        echo json_encode(["error" => "username sudah digunakan, gunakan username lain:" . $conn->error]);
        return;
    }

  
    // 🔒 Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO tbl_users (nama, username, password, level) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nama, $username, $password_hash, $level);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Admin berhasil ditambahkan."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menambah admin: " . $conn->error]);
    }

    $stmt->close();
}



// === Fungsi Update Data ===
function updateUsers($conn)
{
    if (!isset($_POST['id_user'])) {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

    $id_user        = intval($_POST['id_user']);
    $nama           = $_POST['nama'];
    $username       = trim($_POST['username'] ?? '');
    $password       = trim($_POST['password'] ?? '');
    $level          = filter_input(INPUT_POST, 'level', FILTER_VALIDATE_INT);


    // Validasi dasar
    if (empty($username) || !$level) {
        echo json_encode(["error" => "username dan level wajib diisi."]);
        return;
    }

    $cek = $conn->prepare("SELECT COUNT(*) FROM tbl_users WHERE username = ? AND id_user != ?");
    $cek->bind_param("si", $post['username'], $post['id_user']);
    $cek->execute();
    $cek->bind_result($count_username);
    $cek->fetch();
    $cek->close();

    if ($count_username > 0) {
        echo json_encode(["error" => "username sudah digunakan, gunakan username lain:" . $conn->error]);
        return;
    }

    // Jika password tidak kosong, update semuanya termasuk password
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = $conn->prepare("
            UPDATE tbl_users SET nama = ?, username = ?, password = ?, level = ? WHERE id_user = ?
        ");
        $sql->bind_param("sssii", $nama, $username, $hashedPassword, $level, $id_user);
    } else {
        // Kalau password tidak diisi, jangan ubah password
        $sql = $conn->prepare("
            UPDATE tbl_users SET nama = ?, username = ?, level = ? WHERE id_user = ?
        ");
        $sql->bind_param("ssii", $nama, $username, $level, $id_user);
    }

    if ($sql->execute()) {
        echo json_encode(["success" => true, "message" => "Data admin berhasil diperbarui."]);
    } else {
        echo json_encode(["error" => "Gagal update data: " . $sql->error]);
    }

    $sql->close();
}



// === Fungsi Hapus Data ===
function deleteUsers($conn, $id_user)
{
    if (!isset($_POST['id_user'])) {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

    $id_user        = $_POST['id_user'];

    $sql = $conn->prepare("DELETE FROM tbl_users WHERE id_user = ?");
    $sql->bind_param("i", $id_user);

    if ($sql->execute()) {
        echo json_encode(["message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus data: " . $conn->error]);
    }

    $sql->close();
}

$conn->close();
