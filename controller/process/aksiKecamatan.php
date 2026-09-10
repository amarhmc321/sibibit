<?php
header("Content-Type: application/json");
include '../config/database.php';
$action = isset($_GET['action']) ? $_GET['action'] : '';

$id_kecamatan = "";
if (isset($_GET['id_kecamatan'])) {
    $id_kecamatan = $_GET['id_kecamatan'];
}

switch ($action) {
    case 'read':
        readKecamatan($conn);
        break;
    case 'detail':
        detailKecamatan($conn, $id_kecamatan);
        break;
    case 'create':
        createKecamatan($conn);
        break;
    case 'update':
        updateKecamatan($conn);
        break;
    case 'delete':
        deleteKecamatan($conn);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}

// === Fungsi Ambil Data ===


function readKecamatan(mysqli $conn): void
{
    $sql = "
        SELECT 
            u.id_kecamatan,
            u.nama_kecamatan,
            COUNT(DISTINCT o.id_desa) AS total_desa
        FROM tbl_kecamatan u      
        LEFT JOIN tbl_desa o ON u.id_kecamatan = o.id_kecamatan
        GROUP BY u.id_kecamatan, u.nama_kecamatan
    ";

    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Gagal mengambil data Kategori",
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
function detailKecamatan($conn, $id_kecamatan){
   
    // =====================
    // Validasi ID
    // =====================
    if (empty($id_kecamatan) || !is_numeric($id_kecamatan)) {
        echo json_encode([
            "status"  => "error",
            "message" => "ID Kategori tidak valid"
        ]);
        return;
    }

    // =====================
    // Query pakai prepared statement
    // =====================
    $stmt = $conn->prepare("
        SELECT *
        FROM tbl_kecamatan
        WHERE id_kecamatan = ?
    ");
    $stmt->bind_param("i", $id_kecamatan);

    if (!$stmt->execute()) {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal mengambil data Kategori"
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
            "message" => "Data Kategori tidak ditemukan"
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
function createKecamatan($conn){
   
    // =====================
    // Ambil & validasi input
    // =====================
    // $kd_kecamatan   = trim($_POST['kd_kecamatan'] ?? '');
    $nama_kecamatan = trim($_POST['nama_kecamatan'] ?? '');

   

    // =====================
    // Insert data Kategori
    // =====================
    $stmt = $conn->prepare("
        INSERT INTO tbl_kecamatan (nama_kecamatan)
        VALUES (?)
    ");
    $stmt->bind_param("s", $nama_kecamatan);

    if (!$stmt->execute()) {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal menambahkan Kategori"
        ]);
        return;
    }

    echo json_encode([
        "status"  => "success",
        "message" => "Kategori berhasil ditambahkan",
        "id_kecamatan" => $conn->insert_id
    ]);
}



function updateKecamatan($conn){
    // =====================
    // Ambil & validasi input
    // =====================
    $id_kecamatan   = $_POST['id_kecamatan'] ?? '';
    // $kd_kecamatan   = trim($_POST['kd_kecamatan'] ?? '');
    $nama_kecamatan = trim($_POST['nama_kecamatan'] ?? '');

    if (empty($id_kecamatan) || !is_numeric($id_kecamatan)) {
        echo json_encode([
            "status"  => "error",
            "message" => "ID Kategori tidak valid"
        ]);
        return;
    }

    // if ($kd_kecamatan === '' || $nama_kecamatan === '') {
    //     echo json_encode([
    //         "status"  => "error",
    //         "message" => "Kode Kategori dan nama Kategori wajib diisi"
    //     ]);
    //     return;
    // }

    // =====================
    // Cek data Kategori ada
    // =====================
    $cekKategori = $conn->prepare("
        SELECT id_kecamatan 
        FROM tbl_kecamatan 
        WHERE id_kecamatan = ?
    ");
    $cekKategori->bind_param("i", $id_kecamatan);
    $cekKategori->execute();
    $cekKategori->store_result();

    if ($cekKategori->num_rows === 0) {
        echo json_encode([
            "status"  => "error",
            "message" => "Data Kategori tidak ditemukan"
        ]);
        return;
    }
    $cekKategori->close();

    // =====================
    // Cek duplikasi kd_kecamatan (kecuali diri sendiri)
    // =====================
    // $cekKode = $conn->prepare("
    //     SELECT id_kecamatan 
    //     FROM tbl_kecamatan 
    //     WHERE kd_kecamatan = ? AND id_kecamatan != ?
    // ");
    // $cekKode->bind_param("si", $kd_kecamatan, $id_kecamatan);
    // $cekKode->execute();
    // $cekKode->store_result();

    // if ($cekKode->num_rows > 0) {
    //     echo json_encode([
    //         "status"  => "error",
    //         "message" => "Kode Kategori sudah digunakan Kategori lain"
    //     ]);
    //     return;
    // }
    // $cekKode->close();

    // =====================
    // Update data
    // =====================
    $stmt = $conn->prepare("
        UPDATE tbl_kecamatan
        SET  nama_kecamatan = ?
        WHERE id_kecamatan = ?
    ");
    $stmt->bind_param("si", $nama_kecamatan, $id_kecamatan);

    if (!$stmt->execute()) {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal memperbarui Kategori"
        ]);
        return;
    }

    echo json_encode([
        "status"  => "success",
        "message" => "Kategori berhasil diperbarui"
    ]);
}


// === Fungsi Hapus Data ===
function deleteKecamatan($conn)
{
    if (!isset($_POST['id_kecamatan'])) {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }
    $id_kecamatan = $_POST['id_kecamatan'];
    $sql = $conn->prepare("DELETE FROM tbl_kecamatan WHERE id_kecamatan = ?");
    $sql->bind_param("s", $id_kecamatan);

    if ($sql->execute()) {
        echo json_encode(["message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus data: " . $conn->error]);
    }

    $sql->close();
}

$conn->close();
