<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_bibit = "";

if (isset($_GET['id_bibit'])) {
    $id_bibit = $_GET['id_bibit'];
}

switch ($action) {
    case 'read2':
        readBibitUser($conn);
        break;
    case 'read':
        readBibit($conn);
        break;
    case 'detail':
        detailBibit($conn, $id_bibit);
        break;
    case 'create':
        createBibit($conn);
        break;
    case 'update':
        updateBibit($conn);
        break;
    case 'delete':
        deleteBibit($conn);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}

// === Fungsi Ambil Data ===


function readBibitUser(mysqli $conn): void
{
    // Sesuaikan 'id_petani' dengan key session user Anda
    $id_petani = $_SESSION['id_user'] ?? 0; 

    $sql = "SELECT b.*, p.id_pengajuan, p.s_pengajuan 
            FROM tbl_bibit b 
            LEFT JOIN tbl_pengajuan p 
                ON b.id_bibit = p.id_bibit AND p.id_petani = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_petani);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Gagal mengambil data",
            "error" => $conn->error
        ]);
        return;
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}


function readBibit(mysqli $conn): void
{
    $sql = "SELECT * FROM tbl_bibit";
    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Gagal mengambil data Data",
            "error" => $conn->error
        ]);
        return;
    }

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}


// === Fungsi Ambil Data Detail/edit ===
function detailBibit($conn, $id_bibit){
   
    // =====================
    // Validasi ID
    // =====================
    if (empty($id_bibit) || !is_numeric($id_bibit)) {
        echo json_encode([
            "status"  => "error",
            "message" => "ID Data tidak valid"
        ]);
        return;
    }

    // =====================
    // Query pakai prepared statement
    // =====================
    $stmt = $conn->prepare("
        SELECT *
        FROM tbl_bibit
        WHERE id_bibit = ?
    ");
    $stmt->bind_param("i", $id_bibit);

    if (!$stmt->execute()) {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal mengambil data Data"
        ]);
        return;
    }

    $result = $stmt->get_result();

    // =====================
    // Cek data ditemukan
    // =====================
    if ($result->num_rows === 0) {
        echo json_encode([
            "status"  => "error",
            "message" => "Data Data tidak ditemukan"
        ]);
        return;
    }

    $data = $result->fetch_assoc();
    $stmt->close();

    // =====================
    // Response sukses
    // =====================
    echo json_encode($data);
}


// === Fungsi Tambah Data ===
function createBibit($conn){
   
    // =====================
    // Ambil & validasi input
    // =====================
    $nama_bibit = trim($_POST['nama_bibit'] ?? '');
    $jml_per_hektar = isset($_POST['jml_per_hektar']) ? intval($_POST['jml_per_hektar']) : 100;
    $satuan = trim($_POST['satuan'] ?? 'pohon');
    if (empty($satuan)) $satuan = 'pohon';
    // $stock = $_POST['stock'];
    $desk         = $_POST['desk'];
    $status       = $_POST['status'];

    $file = null;
        // Sesuai HTML sebelumnya, name input file adalah 'foto'
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $tmp_name  = $_FILES['foto']['tmp_name'];
            $file_ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            
            // Buat nama file unik untuk mencegah bentrok
            $file = time() . '_' . uniqid() . '.' . $file_ext; 
            
            // Sesuaikan direktori ini dengan struktur folder Anda
            $upload_dir = '../../img/bibit/'; 
            
            if (!move_uploaded_file($tmp_name, $upload_dir . $file)) {
                throw new Exception("Gagal mengupload file.");
            }
        }
   

    // =====================
    // Insert data Data
    // =====================
    $stmt = $conn->prepare("
        INSERT INTO tbl_bibit (nama_bibit, jml_per_hektar, satuan, foto, desk, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssi", $nama_bibit, $jml_per_hektar, $satuan, $file, $desk, $status);

    if (!$stmt->execute()) {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal menambahkan Data"
        ]);
        return;
    }

    echo json_encode([
        "status"  => "success",
        "message" => "Data berhasil ditambahkan",
        "id_bibit" => $conn->insert_id
    ]);
}



function updateBibit($conn){
    // =====================
    // Ambil & validasi input
    // =====================
    $id_bibit   = $_POST['id_bibit'] ?? '';
    $nama_bibit = trim($_POST['nama_bibit'] ?? '');
    $jml_per_hektar = isset($_POST['jml_per_hektar']) ? intval($_POST['jml_per_hektar']) : 100;
    $satuan = trim($_POST['satuan'] ?? 'pohon');
    if (empty($satuan)) $satuan = 'pohon';
    // $stock      = $_POST['stock'];
    $desk       = $_POST['desk'];
    $status       = $_POST['status'];

    if (empty($id_bibit) || !is_numeric($id_bibit)) {
        echo json_encode([
            "status"  => "error",
            "message" => "ID Data tidak valid"
        ]);
        return;
    }



    

    // =====================
    // Cek data
    // =====================
    $cek = $conn->prepare("
        SELECT id_bibit 
        FROM tbl_bibit 
        WHERE id_bibit = ?
    ");
    $cek->bind_param("i", $id_bibit);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows === 0) {
        echo json_encode([
            "status"  => "error",
            "message" => "Data Data tidak ditemukan"
        ]);
        return;
    }
    $cek->close();


     // =====================
        // Ambil Nama File Lama
        // =====================
        $stmt_old = $conn->prepare("SELECT foto FROM tbl_bibit WHERE id_bibit = ?");
        $stmt_old->bind_param("i", $id_bibit);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        
        if ($result_old->num_rows === 0) {
            throw new Exception("Data pengajuan tidak ditemukan.");
        }
        
        $row_old = $result_old->fetch_assoc();
        $file_lama = $row_old['foto'];
        $stmt_old->close();

        // Default: gunakan nama file lama
        $nama_file = $file_lama;
        $upload_dir = '../../img/bibit/'; // Sesuaikan dengan direktori Anda

        // =====================
        // Proses Upload File Baru (Jika ada)
        // =====================
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $tmp_name  = $_FILES['foto']['tmp_name'];
            $file_ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            
            // Buat nama file unik baru
            $nama_file_baru = time() . '_' . uniqid() . '.' . $file_ext; 
            
            if (!move_uploaded_file($tmp_name, $upload_dir . $nama_file_baru)) {
                throw new Exception("Gagal mengupload filebaru.");
            }

            // Tetapkan nama file baru untuk disimpan ke database
            $nama_file = $nama_file_baru;

            // Hapus file lama dari server untuk menghemat ruang (opsional tapi disarankan)
            if (!empty($file_lama) && file_exists($upload_dir . $file_lama)) {
                unlink($upload_dir . $file_lama);
            }
        }


    // =====================
    // Update data
    // =====================
    $stmt = $conn->prepare("
        UPDATE tbl_bibit
        SET nama_bibit = ?, jml_per_hektar = ?, satuan = ?, foto = ?, desk = ?, status = ?
        WHERE id_bibit = ?
    ");
    $stmt->bind_param("sisssii", $nama_bibit, $jml_per_hektar, $satuan, $nama_file, $desk, $status, $id_bibit);

    if (!$stmt->execute()) {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal memperbarui Data"
        ]);
        return;
    }

    echo json_encode([
        "status"  => "success",
        "message" => "Data berhasil diperbarui"
    ]);
}


// === Fungsi Hapus Data ===
function deleteBibit($conn)
{
    if (!isset($_POST['id_bibit'])) {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }
    $id_bibit = $_POST['id_bibit'];
    $sql = $conn->prepare("DELETE FROM tbl_bibit WHERE id_bibit = ?");
    $sql->bind_param("s", $id_bibit);

    if ($sql->execute()) {
        echo json_encode(["message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus data: " . $conn->error]);
    }

    $sql->close();
}

$conn->close();
