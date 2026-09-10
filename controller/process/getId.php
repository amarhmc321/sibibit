<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_kategori= isset($_GET['id_kategori']) ? $_GET['id_kategori'] : '';


switch ($action) {
    case 'get_next_kode':
        generate_next_kode($id_kategori);
        break;
   
    case 'get_next_username':
       generate_next_username();
        break;
    case 'select-petani':
        selectPetani($conn);
        break;
    case 'select-group':
        selectGroup($conn);
        break;
    
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}




function selectPetani($conn){
    $term = $_GET['term'] ?? '';
    $search = "%$term%";

    $sql = "
        SELECT id_petani, nama_petani 
        FROM tbl_petani
        WHERE (id_petani LIKE ? OR nama_petani LIKE ?)
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => $conn->error]);
        exit;
    }

    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    $output = [];

    while ($row = $result->fetch_assoc()) {
        $output[] = [
            'id' => $row['id_petani'],
            'text'    => $row['nama_petani']
        ];
    }

    echo json_encode($output);
    exit;
}

function selectGroup($conn){
    $term = $_GET['term'] ?? '';
    $search = "%$term%";

    $sql = "
        SELECT id_group, nama_kelompok 
        FROM tbl_groups
        WHERE (id_group LIKE ? OR nama_kelompok LIKE ?)
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => $conn->error]);
        exit;
    }

    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    $output = [];

    while ($row = $result->fetch_assoc()) {
        $output[] = [
            'id' => $row['id_group'],
            'text'    => $row['nama_kelompok']
        ];
    }

    echo json_encode($output);
    exit;
}







function generate_next_Id()
{
    global $conn;

    // Ambil ID karyawan terakhir
    $query = "SELECT MAX(id_penulis) AS last_Id FROM tbl_penulis";
    $result = $conn->query($query);

    if (!$result) {
        return '0001'; // Jika query gagal, mulai dari 1
    }

    $row = $result->fetch_assoc();

    // Jika tidak ada data atau last_Id kosong
    $next_number = empty($row['last_Id']) ? 1 : (int)$row['last_Id'] + 1;

    // Format tampilan ke 4 digit dengan leading zero
    return str_pad($next_number, 4, '0', STR_PAD_LEFT);
}



function generate_next_kode($id_kategori)
{
    global $conn;

    // =====================
    // 1. Ambil kode Kategori
    // =====================
    $q1 = $conn->prepare("
        SELECT kd_kategori 
        FROM tbl_kategori 
        WHERE id_kategori = ?
    ");
    $q1->bind_param("i", $id_kategori);
    $q1->execute();
    $res1 = $q1->get_result();

    if ($res1->num_rows === 0) {
        throw new Exception("Kategori tidak ditemukan");
    }

    $kd_kategori = $res1->fetch_assoc()['kd_kategori'];
    $q1->close();

    // =====================
    // 2. Ambil nomor terakhir SC per Kategori
    // =====================
    $q2 = $conn->prepare("SELECT MAX(CAST(SUBSTRING_INDEX(kd_obat, '-', -1) AS UNSIGNED)) AS last_number FROM tbl_obat
        WHERE kd_obat LIKE ?
    ");

    $pattern = "OBT-$kd_kategori-%";
    $q2->bind_param("s", $pattern);
    $q2->execute();
    $res2 = $q2->get_result();
    $row  = $res2->fetch_assoc();

    $last_number = $row['last_number'] ?? 0;
    $q2->close();

    // =====================
    // 3. Generate kode baru
    // =====================
    $next_number = $last_number + 1;
    $next_kd_obat  = "OBT-$kd_kategori-" . str_pad($next_number, 3, '0', STR_PAD_LEFT);

    echo json_encode($next_kd_obat);
}



function generate_next_username(){
    global $conn;


    $queryUsers = "SELECT MAX(username) AS last_username 
                   FROM tbl_users 
                   WHERE username REGEXP '^Petani[0-9]+$'";
    $resultUsers = $conn->query($queryUsers);

    if (!$resultUsers) {
        $last_username_num = 0;
    } else {
        $rowUsers = $resultUsers->fetch_assoc();
        if ($rowUsers['last_username']) {
            // Ambil angka dari username terakhir (misal: sjs007 → 7)
            $last_username_num = (int)preg_replace('/[^0-9]/', '', $rowUsers['last_username']);
        } else {
            $last_username_num = 0;
        }
    }


    $next_username = "Petani" . str_pad($last_username_num + 1, 3, '0', STR_PAD_LEFT);

    echo json_encode($next_username);
}

